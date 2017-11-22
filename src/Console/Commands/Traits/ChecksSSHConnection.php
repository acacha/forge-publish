<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait ChecksSSHConnection.
 *
 * @package Acacha\ForgePublish\Commands\Traits
 */
trait ChecksSSHConnection
{
    /**
     * Check SSH connection.
     *
     * @return bool
     */
    protected function checkSSHConnection($server)
    {
        $ret = exec('sudo -u ' . get_current_user() . ' timeout 10 ssh -q ' . $server . ' "exit"; echo $?');
        if ($ret == 0 ) return true;
        return false;
    }

    /**
     * Abort if no SSH connection.
     *
     * @return bool
     */
    protected function abortIfNoSSHConnection($server)
    {
        if (!$this->checkSSHConnection($server)) {
            $this->error("SSH connection to server $server doesn't works. Please run php artisan publish:init or publish:ssh");
            die();
        }
    }

}