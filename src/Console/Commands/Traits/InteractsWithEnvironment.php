<?php

namespace Acacha\ForgePublish\Commands\Traits;

use function Couchbase\passthruDecoder;
use Illuminate\Support\Facades\File;

/**
 * Trait InteractsWithEnvironment
 *
 * @package Acacha\ForgePublish\Commands
 */
trait InteractsWithEnvironment
{
    /**
     * Add value to env.
     *
     * @param $key
     * @param $value
     */
    protected function addValueToEnv($key, $value)
    {
        $env_path = base_path('.env');
        $sed_command = "/bin/sed -i '/^$key/d' " . $env_path;
        passthru($sed_command);
        File::append($env_path, "\n$key=$value\n");
    }
}