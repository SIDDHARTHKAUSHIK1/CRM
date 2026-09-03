<?php

use Crm\WhatsApp\Services\PhoneParserService;

beforeEach(function () {
    $this->parser = new PhoneParserService('91');
});

test('it normalizes 10-digit phone numbers by prepending default country code', function () {
    $result = $this->parser->normalizeNumber('9876543210');

    expect($result['valid'])->toBeTrue()
        ->and($result['phone_e164'])->toBe('919876543210');
});

test('it normalizes 11-digit numbers with leading zero', function () {
    $result = $this->parser->normalizeNumber('09876543210');

    expect($result['valid'])->toBeTrue()
        ->and($result['phone_e164'])->toBe('919876543210');
});

test('it normalizes numbers with leading plus sign and country code', function () {
    $result = $this->parser->normalizeNumber('+919876543210');

    expect($result['valid'])->toBeTrue()
        ->and($result['phone_e164'])->toBe('919876543210');
});

test('it normalizes numbers with spaces, hyphens, and parentheses', function () {
    $result = $this->parser->normalizeNumber('+91 (987) 654-3210');

    expect($result['valid'])->toBeTrue()
        ->and($result['phone_e164'])->toBe('919876543210');
});

test('it handles Excel scientific notation gracefully', function () {
    $result = $this->parser->normalizeNumber('9.1987654321E+11');

    expect($result['valid'])->toBeTrue()
        ->and($result['phone_e164'])->toBe('919876543210');
});

test('it rejects numbers that are too short', function () {
    $result = $this->parser->normalizeNumber('12345');

    expect($result['valid'])->toBeFalse()
        ->and($result['reason'])->toContain('Too short');
});

test('it rejects text without digits', function () {
    $result = $this->parser->normalizeNumber('invalid_number');

    expect($result['valid'])->toBeFalse()
        ->and($result['reason'])->toBe('No digits found');
});

test('it rejects null or empty values', function () {
    $result = $this->parser->normalizeNumber(null);

    expect($result['valid'])->toBeFalse();
});

test('it parses text file with multiple phone numbers and deduplicates', function () {
    $tempFile = tempnam(sys_get_temp_dir(), 'whatsapp_test_');
    file_put_contents($tempFile, "9876543210\n+919876543210\n9876543211\ninvalid_row\n09876543212\n");

    $result = $this->parser->parseFile($tempFile);
    unlink($tempFile);

    expect($result['valid_count'])->toBe(3)
        ->and($result['duplicate_count'])->toBe(1)
        ->and($result['invalid_count'])->toBe(1);
});

test('it parses comma separated phone numbers on a single line in text file', function () {
    $tempFile = tempnam(sys_get_temp_dir(), 'whatsapp_comma_test_');
    file_put_contents($tempFile, "9105830551 ,  7017848005\n");

    $result = $this->parser->parseFile($tempFile);
    unlink($tempFile);

    expect($result['valid_count'])->toBe(2)
        ->and($result['valid'][0]['phone_e164'])->toBe('919105830551')
        ->and($result['valid'][1]['phone_e164'])->toBe('917017848005');
});

test('it parses multiple phone columns in a csv row', function () {
    $tempFile = tempnam(sys_get_temp_dir(), 'whatsapp_csv_test_');
    file_put_contents($tempFile, "9105830551,7017848005\n");

    $result = $this->parser->parseFile($tempFile);
    unlink($tempFile);

    expect($result['valid_count'])->toBe(2)
        ->and($result['valid'][0]['phone_e164'])->toBe('919105830551')
        ->and($result['valid'][1]['phone_e164'])->toBe('917017848005');
});

