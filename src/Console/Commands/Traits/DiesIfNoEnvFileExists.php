<?php

namespace Acacha\ForgePublish\Commands\Traits;

use Illuminate\Support\Facades\File;

/**
 * Trait DiesIfNoEnvFileExists
 *
 * @package Acacha\ForgePublish\Commands
 */
trait DiesIfNoEnvFileExists
{
    /**
     * Skip if no .env file found.
     */
    protected function dieIfNoEnvFileIsFound()
    {
        if (! File::exists(base_path('.env')) ) {
            $this->info('No .env file found!');
            $this->info('Skipping...');
            die();
        }
    }
}