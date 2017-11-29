<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait ItFetchesMySQLDatabases
 *
 * @package Acacha\ForgePublish\Commands
 */
trait ItFetchesMySQLDatabases
{
    /**
     * Fetch MySQLDatabases.
     */
    protected function fetchMySQLDatabases()
    {
        $uri = str_replace('{forgeserver}', $this->server, config('forge-publish.get_mysql_uri'));
        $this->url = config('forge-publish.url') . $uri;
        try {
            $response = $this->http->get($this->url, [
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . fp_env('ACACHA_FORGE_ACCESS_TOKEN')
                ]
            ]);
        } catch (\Exception $e) {
            $this->error('And error occurs connecting to the api url: ' . $this->url);
            $this->error('Status code: ' . $e->getResponse()->getStatusCode() . ' | Reason : ' . $e->getResponse()->getReasonPhrase());
            return [];
        }
        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Get mysql database from databases by name.
     *
     * @param $databases
     * @param $database_name
     * @return mixed|null
     */
    protected function getMySQLDatabaseByName($databases, $database_name)
    {
        $database_found = collect($databases)->filter(function ($database) use ($database_name) {
            return $database['name'] === $database_name;
        })->first();

        if ($database_found) {
            return $database_found;
        }
        return null;
    }
}
