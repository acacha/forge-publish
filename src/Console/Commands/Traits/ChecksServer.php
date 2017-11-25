<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait ChecksServer.
 * 
 * @package Acacha\ForgePublish\Commands\Traits
 */
trait ChecksServer
{
    use ItFetchesServers;

    /**
     * Check server.
     *
     * @return bool
     */
    protected function checkServer() {
        $servers = $this->fetchServers();
        return in_array(fp_env('ACACHA_FORGE_SERVER'), collect($servers)->pluck('forge_id')->toArray());
    }

}