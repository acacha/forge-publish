<?php

namespace Acacha\ForgePublish\Commands\Traits;

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
        File::append(base_path('.env'), "\n$key=$value\n");
    }
}