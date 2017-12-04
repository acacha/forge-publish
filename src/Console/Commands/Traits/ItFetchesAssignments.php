<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait ItFetchesAssignments
 *
 * @package Acacha\ForgePublish\Commands
 */
trait ItFetchesAssignments
{
    /**
     * Fetch sites
     */
    public function fetchAssignments()
    {
        $url = config('forge-publish.url') . config('forge-publish.teacher_assignments_uri');
        try {
            $response = $this->http->get($url, [
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . fp_env('ACACHA_FORGE_ACCESS_TOKEN')
                ]
            ]);
        } catch (\Exception $e) {
            $this->error('And error occurs connecting to the api url: ' . $url);
            $this->error('Status code: ' . $e->getResponse()->getStatusCode() . ' | Reason : ' . $e->getResponse()->getReasonPhrase());
            return [];
        }
        return json_decode((string) $response->getBody());
    }

}
