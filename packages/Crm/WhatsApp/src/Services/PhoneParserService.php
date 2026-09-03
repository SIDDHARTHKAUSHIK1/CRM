<?php

namespace Crm\WhatsApp\Services;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\UploadedFile;

class PhoneParserService
{
    /**
     * Default country code (without +).
     */
    protected string $defaultCountryCode;

    public function __construct(?string $defaultCountryCode = null)
    {
        $this->defaultCountryCode = $defaultCountryCode ?: (string) config('whatsapp.default_country_code', '91');
    }

    /**
     * Parse phone numbers from an uploaded file or file path.
     *
     * @param  string|UploadedFile  $file
     * @return array
     */
    public function parseFile($file): array
    {
        return $this->processRows($this->extractRawRows($file));
    }

    /**
     * Parse phone numbers from a manual text string.
     *
     * @param  string  $text
     * @return array
     */
    public function parseManualText(string $text): array
    {
        return $this->processRows($this->splitTextIntoRows($text));
    }

    /**
     * Parse phone numbers from both a file and manual text, merging and deduplicating in a single pass.
     *
     * @param  string|UploadedFile|null  $file
     * @param  string|null  $manualText
     * @return array
     */
    public function parseCombined($file, ?string $manualText): array
    {
        $rawRows = [];

        if ($file) {
            $rawRows = array_merge($rawRows, $this->extractRawRows($file));
        }

        if ($manualText !== null && trim($manualText) !== '') {
            $rawRows = array_merge($rawRows, $this->splitTextIntoRows($manualText));
        }

        return $this->processRows($rawRows);
    }

    /**
     * Process raw rows into valid, invalid, and duplicate records.
     *
     * @param  array  $rawRows
     * @return array
     */
    protected function processRows(array $rawRows): array
    {
        $valid = [];
        $invalid = [];
        $duplicates = [];
        $seen = [];
        $totalRows = 0;

        foreach ($rawRows as $index => $row) {
            // Check if first row is a column header (e.g. "phone", "number", "mobile")
            if ($index === 0 && $this->isHeaderRow($row)) {
                continue;
            }

            $candidates = $this->extractCandidateValuesFromRow($row);

            foreach ($candidates as $rawVal) {
                if ($rawVal === null || trim((string) $rawVal) === '') {
                    continue;
                }

                $rawString = trim((string) $rawVal);

                $totalRows++;
                $result = $this->normalizeNumber($rawString);

                if ($result['valid']) {
                    $e164 = $result['phone_e164'];

                    if (isset($seen[$e164])) {
                        $duplicates[] = [
                            'raw'        => $rawString,
                            'phone_e164' => $e164,
                            'reason'     => 'Duplicate number in upload',
                        ];
                    } else {
                        $seen[$e164] = true;
                        $valid[] = [
                            'raw'        => $rawString,
                            'phone_e164' => $e164,
                        ];
                    }
                } else {
                    $invalid[] = [
                        'raw'    => $rawString,
                        'reason' => $result['reason'],
                    ];
                }
            }
        }

        return [
            'total_rows'      => $totalRows,
            'valid_count'     => count($valid),
            'invalid_count'   => count($invalid),
            'duplicate_count' => count($duplicates),
            'valid'           => $valid,
            'invalid'         => $invalid,
            'duplicates'      => $duplicates,
        ];
    }

    /**
     * Extract raw rows from CSV, Excel, or TXT file.
     */
    protected function extractRawRows($file): array
    {
        $extension = '';
        $filePath = '';

        if ($file instanceof UploadedFile) {
            $extension = strtolower($file->getClientOriginalExtension() ?: pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));
            $filePath = $file->getRealPath() ?: $file->path();
        } else {
            $extension = strtolower(pathinfo((string) $file, PATHINFO_EXTENSION));
            $filePath = (string) $file;
        }

        if (in_array($extension, ['xlsx', 'xls'])) {
            try {
                $sheets = Excel::toCollection(null, $file);
                $rows = [];

                foreach ($sheets as $sheet) {
                    foreach ($sheet as $row) {
                        $rows[] = $row->toArray();
                    }
                }

                if (! empty($rows)) {
                    return $rows;
                }
            } catch (\Throwable $e) {
                // Ignore
            }
        }

        if ($extension === 'csv') {
            $rows = $this->readCsvFallback($filePath);
            if (! empty($rows)) {
                return $rows;
            }
        }

        // Plain text (.txt) or newline/comma separated fallback
        return $this->readTextFile($filePath);
    }

    /**
     * Split raw text content into rows and tokens.
     */
    protected function splitTextIntoRows(string $content): array
    {
        // Split by newlines
        $lines = preg_split('/[\r\n]+/', $content);
        $rows = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            // In text content, numbers can be separated by commas, semicolons, tabs, pipes, or slashes
            if (preg_match('/[,;\t|\/]/', $line)) {
                $parts = preg_split('/[,;\t|\/]+/', $line);
                foreach ($parts as $part) {
                    $part = trim($part);
                    if ($part !== '') {
                        $rows[] = [$part];
                    }
                }
            } else {
                $rows[] = [$line];
            }
        }

        return $rows;
    }

    /**
     * Read plain text file rows.
     */
    protected function readTextFile(string $filePath): array
    {
        $content = file_get_contents($filePath);
        if ($content === false) {
            return [];
        }

        return $this->splitTextIntoRows($content ?: '');
    }

    /**
     * Fallback CSV reader.
     */
    protected function readCsvFallback(string $filePath): array
    {
        $rows = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }
        return $rows;
    }

    /**
     * Extract all candidate phone number strings from a row.
     *
     * @param  mixed  $row
     * @return array<string>
     */
    protected function extractCandidateValuesFromRow(mixed $row): array
    {
        if (! is_array($row)) {
            $row = [$row];
        }

        $phoneLike = [];
        $fallback = [];

        foreach ($row as $cell) {
            if ($cell === null) {
                continue;
            }

            $cellStr = trim((string) $cell);
            if ($cellStr === '') {
                continue;
            }

            // If cell contains delimiters like comma, semicolon, pipe, or slash
            if (preg_match('/[,;|\/]/', $cellStr)) {
                $subParts = preg_split('/[,;|\/]+/', $cellStr);
                foreach ($subParts as $sub) {
                    $sub = trim($sub);
                    if ($sub === '') {
                        continue;
                    }
                    if (preg_match('/\d{5,}/', $sub)) {
                        $phoneLike[] = $sub;
                    } else {
                        $fallback[] = $sub;
                    }
                }
            } else {
                if (preg_match('/\d{5,}/', $cellStr)) {
                    $phoneLike[] = $cellStr;
                } else {
                    $fallback[] = $cellStr;
                }
            }
        }

        if (! empty($phoneLike)) {
            return $phoneLike;
        }

        if (! empty($fallback)) {
            return [reset($fallback)];
        }

        return [];
    }

    /**
     * Check if string or row is a typical header label.
     */
    protected function isHeaderRow(mixed $row): bool
    {
        if (is_array($row)) {
            // If any cell has 5+ digits, it's real data, not a header row
            foreach ($row as $cell) {
                if ($cell !== null && preg_match('/\d{5,}/', (string) $cell)) {
                    return false;
                }
            }

            foreach ($row as $cell) {
                if ($cell !== null && $this->isHeaderKeyword((string) $cell)) {
                    return true;
                }
            }

            return false;
        }

        return $this->isHeaderKeyword((string) $row);
    }

    protected function isHeaderKeyword(string $value): bool
    {
        $normalized = strtolower(preg_replace('/[^a-z]/', '', $value));
        $headerKeywords = ['phone', 'phonenumber', 'mobile', 'mobilenumber', 'contact', 'telephone', 'number', 'whatsapp', 'cell', 'alternatephone'];

        return in_array($normalized, $headerKeywords, true);
    }

    /**
     * Normalize raw phone number string to standard E.164 (without +).
     */
    public function normalizeNumber(mixed $raw): array
    {
        if ($raw === null) {
            return ['valid' => false, 'reason' => 'Empty value'];
        }

        $str = (string) $raw;

        // Handle scientific notation (e.g. 9.1987654321E+11 or 9.19E+11)
        if (is_numeric($raw) && (is_float($raw) || stripos($str, 'e+') !== false)) {
            $str = sprintf('%.0f', (float) $raw);
        }

        // Remove all non-digit characters except leading +
        $hasLeadingPlus = str_starts_with(trim($str), '+');
        $digits = preg_replace('/\D/', '', $str);

        if (empty($digits)) {
            return ['valid' => false, 'reason' => 'No digits found'];
        }

        // Strip leading 00 (international prefix)
        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }

        // If 11 digits starting with 0, strip leading 0 (e.g. 09876543210 -> 9876543210)
        if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        // If 10 digits, prepend configured default country code
        if (strlen($digits) === 10) {
            $digits = $this->defaultCountryCode . $digits;
        }

        // Check E.164 bounds (8 to 15 digits, cannot start with 0)
        $len = strlen($digits);
        if ($len < 8) {
            return ['valid' => false, 'reason' => "Too short ($len digits, minimum 8 required)"];
        }

        if ($len > 15) {
            return ['valid' => false, 'reason' => "Too long ($len digits, maximum 15 allowed)"];
        }

        if (str_starts_with($digits, '0')) {
            return ['valid' => false, 'reason' => 'Invalid country code (starts with 0)'];
        }

        return [
            'valid'      => true,
            'phone_e164' => $digits,
            'reason'     => null,
        ];
    }
}
