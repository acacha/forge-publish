<?php

namespace Acacha\ForgePublish\Commands\Traits;

use Acacha\ForgePublish\Exceptions\TimeoutException;

/**
 * Class Retries.
 *
 * @package Acacha\ForgePublish\Commands\Traits
 */
trait Retries
{

    /**
     * Retry the callback or fail after x seconds.
     *
     * @param $timeout
     * @param $callback
     * @return mixed
     * @throws TimeoutException
     */
    public function retry($timeout, $callback)
    {
        $start = time();

        beginning:

        if ($output = $callback()) {
            return $output;
        }

        if (time() - $start < $timeout) {
            sleep(5);

            goto beginning;
        }

        throw new TimeoutException($output);
    }
}