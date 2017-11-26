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
     * Check server and abort.
     *
     * @param null $server
     */
    protected function checkServerAndAbort($server = null) {
        $server = $server ? $server : fp_env('ACACHA_FORGE_SERVER');
        if (! $this->checkServer($server)) {
            $this->error('Server ' . $server . ' not valid');
            die();
        }
    }

    /**
     * Check value in servers.
     *
     * @param $env_var
     * @param $field
     * @param null $value
     * @param null $servers
     * @return bool
     */
    protected function checkValue($env_var, $field, $value = null, $servers = null) {
        $value = $value ? $value : fp_env($env_var);
        $servers = $servers ? $servers : $this->obtainServers();
        return in_array($value, collect($servers)->pluck($field)->toArray());
    }

    /**
     * Obtain servers.
     *
     * @return array|mixed
     */
    protected function obtainServers()
    {
        return isset($this->servers) ? $this->servers : $this->fetchServers();
    }

    /**
     * Check server id.
     *
     * @param null $server
     * @param null $servers
     * @return bool
     */
    protected function checkServer($server = null, $servers = null) {
        return $this->checkValue('ACACHA_FORGE_SERVER','forge_id',$server, $servers);
    }

    /**
     * Check ip.
     *
     * @param null $ip
     * @param null $servers
     * @return bool
     */
    protected function checkIp($ip = null, $servers = null) {
        return $this->checkValue('ACACHA_FORGE_IP_ADDRESS','ipAddress',$ip, $servers);
    }

    /**
     * Check server name.
     *
     * @param null $server_name
     * @param null $servers
     * @return bool
     */
    protected function checkServerName($server_name = null, $servers = null) {
        return $this->checkValue('ACACHA_FORGE_SERVER_NAME','name',$server_name, $servers);
    }

}