<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait RunsSSHCommands.
 *
 * @package Acacha\ForgePublish\Commands\Traits
 */
trait RunsSSHCommands
{
    /**
     * Runs ssh command on server.
     *
     * @param $server
     * @param $command
     */
    protected function runSSH($server, $command)
    {
        $ssh_config_file = $_SERVER['HOME'] . '/.ssh/config';
        $full_command = "ssh -F $ssh_config_file -t $server '$command'";
        $this->info($full_command);
        passthru($full_command);
    }

    /**
     * Exec ssh command on server.
     *
     * @param $server
     * @param $command
     * @return string
     */
    protected function execSSH($server, $command)
    {
        $ssh_config_file = $_SERVER['HOME'] . '/.ssh/config';
        $full_command = "ssh -F $ssh_config_file -t $server '$command'";
        $this->info($full_command);
        return shell_exec($full_command);
    }
}