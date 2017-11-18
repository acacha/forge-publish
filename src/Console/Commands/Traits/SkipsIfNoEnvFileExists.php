<?php

namespace Acacha\ForgePublish\Commands\Traits;

use Illuminate\Support\Facades\File;

/**
 * Trait SkipsIfNoEnvFileExists
 *
 * @package Acacha\ForgePublish\Commands
 */
trait SkipsIfNoEnvFileExists
{
    /**
     * Skip if no .env file found.
     */
    protected function skipIfNoEnvFileIsFound()
    {
        if (! File::exists(base_path('.env')) ) {
            $this->info('No .env file found!');
            $this->info('Skipping...');
            die();
        }
    }
}