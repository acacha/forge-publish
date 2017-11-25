<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait ChecksSSHConnection.
 *
 * @package Acacha\ForgePublish\Commands\Traits
 */
trait ChecksSSHConnection
{
    use ChecksServer;

    /**
     * SSH config file path.
     *
     * @return string
     */
    protected function sshConfigFile()
    {
        return $_SERVER['HOME'] . '/.ssh/config';
    }

    /**
     * hostname for config file.
     */
    protected function hostNameForConfigFile($server_name = null, $server =null)
    {
        $server_name = $server_name ? $server_name: $this->getServerName();
        $server = $server ? $server: $this->getServer();
        $this->checkServerName($server_name);
        $this->checkServer($server);
        return snake_case($server_name) . '_' . $server;
    }

    /**
     * Get Server name.
     *
     * @return null
     */
    protected function getServerName()
    {
        $server_name = $this->server_name ? $this->server_name : fp_env('ACACHA_FORGE_SERVER_NAME');
        return $server_name;
    }

    /**
     * Get server.
     * @return null
     */
    protected function getServer()
    {
        $server_name = $this->server ? $this->server : fp_env('ACACHA_FORGE_SERVER');
        return $server_name;
    }

    /**
     * Check SSH connection.
     *
     * @return bool
     */
    protected function checkSSHConnection($server, $ssh_config_file = null)
    {
        $ssh_config_file =  $ssh_config_file ? $ssh_config_file : $this->sshConfigFile();
        $this->info("timeout 10 ssh -F $ssh_config_file -q " . $server . ' exit; echo $?');

        $ret = exec("timeout 10 ssh -F $ssh_config_file -q " . $server . ' "exit"; echo $?');
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