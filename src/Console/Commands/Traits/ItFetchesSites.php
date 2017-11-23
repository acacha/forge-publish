<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait ItFetchesSites
 *
 * @package Acacha\ForgePublish\Commands
 */
trait ItFetchesSites
{

    /**
     * Fetch sites
     */
    protected function fetchSites ($server_id)
    {
        $url = config('forge-publish.url') . config('forge-publish.user_sites_uri') . '/' . $server_id;
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

}