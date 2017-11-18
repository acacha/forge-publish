<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait SkipsIfTokenIsAlreadyInstalled
 *
 * @package Acacha\ForgePublish\Commands
 */
trait SkipsIfEnvVariableIsAlreadyInstalled
{
    /**
     * Skip if token is already installed.
     */
    protected function skipIfEnvVarIsAlreadyInstalled($env_var)
    {
        if ( env($env_var, null) != null ) {
            $this->info("The environment variable $env_var already exists in your environment (check .env file).");
            $this->info("Please remove the $env_var an re-execute command. Skipping...");
            die();
        }
    }

}