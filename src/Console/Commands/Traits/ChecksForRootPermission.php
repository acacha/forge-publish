<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait ChecksForRootPermission.
 * 
 * @package Acacha\ForgePublish\Commands\Traits
 */
trait ChecksForRootPermission
{
    /**
     * Check for root permission.
     */
    protected function checkForRootPermission()
    {
        if (posix_geteuid() != 0) {
            $this->error('This command needs root permissions. Please use sudo: ');
            $this->info('sudo php artisan publish:dns');
            die();
        }
    }
}