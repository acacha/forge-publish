<?php

namespace Acacha\ForgePublish\Commands\Traits;

use Acacha\ForgePublish\Exceptions\EnvironmentVariableNotFoundException;

/**
 * Trait AborstIfEnvVariableIsnotInstalled
 *
 * @package Acacha\ForgePublish\Commands
 */
trait AborstIfEnvVariableIsnotInstalled
{
    /**
     * Skip if env var is not installed.
     */
    protected function abortsIfEnvVarIsNotInstalled($env_var)
    {
        if (! $this->fp_env($env_var)) {
            $this->info("No $env_var key found in .env file.");
            $this->info('Please configure this .env variable manually or run php artisan publish:init. Skipping...');
            throw new EnvironmentVariableNotFoundException($env_var);
        }
    }

    /**
     * Get Forge publish env.
     *
     * @param $env_var
     * @return null
     */
    protected function fp_env($env_var) {
        return fp_env($env_var);
    }
}
