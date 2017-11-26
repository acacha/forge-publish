<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait RunsSSHCommands.
 *
 * @package Acacha\ForgePublish\Commands\Traits
 */
trait RunsSSHCommands
{
    use ChecksSSHConnection;

    /**
     * Runs ssh command on server.
     *
     * @param $command
     * @param $server
     */
    protected function runSSH( $command, $server = null)
    {
        $server = $server ? $server : $this->hostNameForConfigFile();
        $ssh_config_file = $_SERVER['HOME'] . '/.ssh/config';
        $full_command = "ssh -F $ssh_config_file -t $server '$command'";
        $this->info($full_command);
        passthru($full_command);
    }

    /**
     * Exec ssh command on server.
     *
     * @param $command
     * @param $server
     * @return string
     */
    protected function execSSH($command, $server = null)
    {
        $server = $server ? $server : $this->hostNameForConfigFile();

        $ssh_config_file = $_SERVER['HOME'] . '/.ssh/config';
        $full_command = "ssh -F $ssh_config_file -t $server '$command'";
        $this->info($full_command);
        return shell_exec($full_command);
    }
}