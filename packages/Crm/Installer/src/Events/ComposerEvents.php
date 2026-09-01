<?php

namespace Crm\Installer\Events;

use Symfony\Component\Console\Output\ConsoleOutput;

class ComposerEvents
{
    /**
     * Post create project.
     *
     * @return void
     */
    public static function postCreateProject()
    {
        $output = new ConsoleOutput;

        $output->writeln(file_get_contents(__DIR__.'/../Templates/on-boarding.php'));

        $output->writeln(self::cloudHostingBox());
    }

    /**
     * Build the CRM Cloud Hosting promotional box for the console output.
     *
     * @return string
     */
    public static function cloudHostingBox()
    {
        $width = 64;

        $background = '#1d4ed8';

        $lines = [
            ['★ CRM CLOUD HOSTING', 'fg=#facc15;options=bold'],
            ['', 'fg=#ffffff'],
            ['Skip the server setup — run CRM on fast, secure,', 'fg=#ffffff'],
            ['cost-effective managed hosting that scales on demand.', 'fg=#ffffff'],
            ['Launch in minutes, without the infrastructure overhead.', 'fg=#ffffff'],
            ['', 'fg=#ffffff'],
            ['→ https://example.com/crm-cloud-hosting/', 'fg=#bae6fd;options=bold'],
        ];

        $blankRow = "<bg={$background}>".str_repeat(' ', $width).'</>';

        $rows = [PHP_EOL, $blankRow];

        foreach ($lines as [$text, $style]) {
            $content = '  '.$text;

            $content .= str_repeat(' ', max(0, $width - mb_strlen($content)));

            $rows[] = "<bg={$background};{$style}>{$content}</>";
        }

        $rows[] = $blankRow;

        return implode(PHP_EOL, $rows).PHP_EOL;
    }
}
