<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait ItFetchesServers
 *
 * @package Acacha\ForgePublish\Commands
 */
trait ItFetchesServers
{
    /**
     * Fetch servers
     */
    protected function fetchServers ($token = null)
    {
        if (!$token) $token = env('ACACHA_FORGE_ACCESS_TOKEN');
        $url = config('forge-publish.url') . config('forge-publish.user_servers_uri');
        try {
            $response = $this->http->get($url,[
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . $token
                ]
            ]);
        } catch (\Exception $e) {
            return [];
        }
        return json_decode((string) $response->getBody());
    }

    /**
     * Get forge id server from server name.
     *
     * @param $servers
     * @param $server_name
     * @return mixed
     */
    protected function getForgeIdServer($servers, $server_name)
    {
        return collect($servers)->filter(function ($server) use ($server_name) {
            return $server->name == $server_name;
        })->first()->forge_id;
    }

    /**
     * Get forge name from forge id.
     *
     * @param $servers
     * @param $server_id
     * @return mixed
     */
    protected function getForgeName($servers, $server_id)
    {
        return collect($servers)->filter(function ($server) use ($server_id) {
            return $server->forge_id == $server_id;
        })->first()->name;
    }

    /**
     *
     * Get server ip addres by forge server id.
     * @param $servers
     * @param $server_id
     * @return mixed
     */
    protected function serverIpAddress($servers, $server_id)
    {
        return collect($servers)->filter(function ($server) use ($server_id) {
            return $server->forge_id == $server_id;
        })->first()->ipAddress;
    }

    /**
     * Search server by property with a specific value.
     *
     * @param $servers
     * @param $property
     * @param $value
     * @return static
     */
    protected function searchServer($servers,$property,$value) {
        return collect($servers)->filter(function ($server) use ($property, $value) {
            return $server->$property == $value;
        });
    }


}