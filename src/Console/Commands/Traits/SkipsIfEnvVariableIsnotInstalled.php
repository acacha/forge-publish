<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait SkipsIfEnvVariableIsnotInstalled
 *
 * @package Acacha\ForgePublish\Commands
 */
trait SkipsIfEnvVariableIsnotInstalled
{
    /**
     * Skip if env var is not installed.
     */
    protected function skipIfEnvVarIsNotInstalled($env_var)
    {
        if ( env($env_var, null) == null ) {
            $this->info("No $env_var key found in .env file.");
            $this->info('Please configure this .env variable manually or run php artisan publish:init. Skipping...');
            die();
        }
    }

}