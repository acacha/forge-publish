<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait SkipsIfEnvVariableIsnotInstalled
 *
 * @package Acacha\ForgePublish\Commands
 */
trait SkipsIfEnvVariableIsnotInstalled
{
    use GetsEnv;

    /**
     * Skip if env var is not installed.
     */
    protected function skipIfEnvVarIsNotInstalled($env_var)
    {
        if ( ! $this->env($env_var) ) {
            $this->info("No $env_var key found in .env file.");
            $this->info('Please configure this .env variable manually or run php artisan publish:init. Skipping...');
            die();
        }
    }

}