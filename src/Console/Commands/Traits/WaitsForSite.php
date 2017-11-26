<?php

namespace Acacha\ForgePublish\Commands\Traits;

use Acacha\ForgePublish\Exceptions\TimeoutException;

/**
 * Class WaitsForSite.
 *
 * @package Acacha\ForgePublish\Commands\Traits
 */
trait WaitsForSite
{
    use ItFetchesSites;

    /**
     * Wait for site to be available by name!
     *
     * @return mixed
     */
    protected function waitForSiteByName($site_name)
    {
        return $this->retry(50, function () use ($site_name){
            $this->sites = $this->fetchSites(fp_env('ACACHA_FORGE_SERVER'));
            $site = $this->getSiteByName($this->sites,$site_name);
            return $site ? $site : null;
        });
    }

    /**
     * Wait for site to be installed!
     *
     * @return mixed
     */
    protected function waitForSite($site_id)
    {
        return $this->retry(50, function () use ($site_id){
            $this->sites = $this->fetchSites(fp_env('ACACHA_FORGE_SERVER'));
            $site = $this->getSite($this->sites,$site_id);
            return $site->status == 'installed' ? $site : null;
        });
    }

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