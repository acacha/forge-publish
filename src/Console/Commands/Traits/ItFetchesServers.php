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
    protected function fetchServers()
    {
        $url = config('forge-publish.url') . config('forge-publish.user_servers_uri');
        try {
            $response = $this->http->get($url,[
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . env('ACACHA_FORGE_ACCESS_TOKEN')
                ]
            ]);
        } catch (\Exception $e) {
            $this->error('And error occurs connecting to the api url: ' . $url);
            $this->error('Status code: ' . $e->getResponse()->getStatusCode() . ' | Reason : ' . $e->getResponse()->getReasonPhrase() );
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
        $found_server = $this->searchServer($servers,'name',$server_name);
        return $found_server->first() ? $found_server->first()->forge_id : null;
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
        $found_server = $this->searchServer($servers,'forge_id',$server_id);
        return $found_server->first() ? $found_server->first()->name : null;
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
        $found_server = $this->searchServer($servers,'forge_id',$server_id);
        return $found_server->first() ? $found_server->first()->ipAddress : null;
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