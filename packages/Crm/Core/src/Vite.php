<?php

namespace Crm\Core;

use Illuminate\Support\Facades\Vite as BaseVite;
use Crm\Core\Exceptions\ViterNotFound;

class Vite
{
    /**
     * Return the asset URL.
     *
     * @return string
     */
    public function asset(string $filename, string $namespace = 'admin')
    {
        $viters = config('crm-vite.viters');

        if (empty($viters[$namespace])) {
            throw new ViterNotFound($namespace);
        }

        $url = trim($filename, '/');

        $viteUrl = trim($viters[$namespace]['package_assets_directory'], '/').'/'.$url;

        return BaseVite::useHotFile($viters[$namespace]['hot_file'])
            ->useBuildDirectory($viters[$namespace]['build_directory'])
            ->asset($viteUrl);
    }

    /**
     * Set crm vite.
     *
     * @return mixed
     */
    public function set(mixed $entryPoints, string $namespace = 'admin')
    {
        $viters = config('crm-vite.viters');

        if (empty($viters[$namespace])) {
            throw new ViterNotFound($namespace);
        }

        return BaseVite::useHotFile($viters[$namespace]['hot_file'])
            ->useBuildDirectory($viters[$namespace]['build_directory'])
            ->withEntryPoints($entryPoints);
    }
}
