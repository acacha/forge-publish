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
     * Runs scp.
     *
     * @param $file
     * @param null $server
     * @param bool $verbose
     */
    protected function runScp( $file, $destination_path , $server = null, $verbose = false)
    {
        $recursive_option = '';
        if (is_dir($file)) $recursive_option = ' -r';
        $server = $server ? $server : $this->hostNameForConfigFile();
        $ssh_config_file = $_SERVER['HOME'] . '/.ssh/config';
        $full_command = "scp -F ${ssh_config_file}$recursive_option $file $server:$destination_path";

        if ($verbose) $this->info($full_command);
        passthru($full_command);
    }

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