<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait SkipsIfTokenIsAlreadyInstalled
 *
 * @package Acacha\ForgePublish\Commands
 */
trait SkipsIfTokenIsAlreadyInstalled
{
    /**
     * Skip if token is already installed.
     */
    protected function skipIfTokenIsAlreadyInstalled()
    {
        if ( env('ACACHA_FORGE_ACCESS_TOKEN', null) != null ) {
            $this->info('An Access Token already exists in your environment (check for ACACHA_FORGE_ACCESS_TOKEN in .env file).');
            $this->info('Please remove the token an re-execute command if you want to relogin.');
            $this->info('Skipping...');
            die();
        }
    }

}