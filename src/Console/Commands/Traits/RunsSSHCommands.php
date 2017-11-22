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
        $full_command = "ssh -t $server '$command'";
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
        $full_command = "ssh -t $server '$command'";
        $this->info($full_command);
        return shell_exec($full_command);
    }
}