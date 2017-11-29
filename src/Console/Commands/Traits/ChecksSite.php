<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait ChecksSite.
 *
 * @package Acacha\ForgePublish\Commands\Traits
 */
trait ChecksSite
{
    use ItFetchesSites;

    /**
     * Check site.
     *
     * @return bool
     */
    protected function checkSite()
    {
        $sites = $this->fetchSites(fp_env('ACACHA_FORGE_SERVER'));
        return in_array(fp_env('ACACHA_FORGE_SITE'), collect($sites)->pluck('id')->toArray());
    }

    /**
     * Check site and abort.
     *
     * @param null $site
     */
    protected function checkSiteAndAbort($site = null)
    {
        $site = $site ? $site : fp_env('ACACHA_FORGE_SITE');
        if (! $this->checkSite($site)) {
            $this->error('Site ' . $site . '  not valid');
            die();
        }
    }
}
