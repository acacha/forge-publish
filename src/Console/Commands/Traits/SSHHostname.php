<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait SSHHostname.
 *
 * @package Acacha\ForgePublish\Commands\Traits
 */
trait SSHHostname
{
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
}
