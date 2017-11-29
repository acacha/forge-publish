<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Class WaitsForMySQLDatabase.
 *
 * @package Acacha\ForgePublish\Commands\Traits
 */
trait WaitsForMySQLDatabase
{
    use ItFetchesMySQLDatabases, Retries;

    /**
     * Wait for MySQL database by name!
     *
     * @return mixed
     */
    protected function waitForMySQLDatabaseByName($database_name)
    {
        return $this->retry(50, function () use ($database_name) {
            $this->databases = $this->fetchMySQLDatabases();
            $database = $this->getMysqlDatabaseByName($this->databases, $database_name);
            return $database['status'] == 'installed' ? $database : null;
        });
    }
}
