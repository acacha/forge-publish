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
                    'Authorization' => 'Bearer ' . fp_env('ACACHA_FORGE_ACCESS_TOKEN')
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
     * Get forge site name from site id.
     *
     * @param $sites
     * @param $site_id
     * @return mixed
     */
    protected function getSiteName($sites, $site_id)
    {
        $site_found = collect($sites)->filter(function ($site) use ($site_id) {
            return $site->id === $site_id;
        })->first();

        if ( $site_found ) return $site_found->name;
        return null;
    }

    /**
     * Get forge site id from site name.
     *
     * @param $sites
     * @param $site_name
     * @return mixed
     */
    protected function getSiteId($sites, $site_name)
    {
        $site_found = collect($sites)->filter(function ($site) use ($site_name) {
            return $site->name === $site_name;
        })->first();

        if ( $site_found ) return $site_found->id;
        return null;
    }
}