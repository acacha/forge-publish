<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ItFetchesSites;
use GuzzleHttp\Client;

/**
 * Class PublishSite.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishSite extends SaveEnvVariable
{
    use ItFetchesSites;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:site {site?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save acacha forge site';

    /**
     * Sites.
     *
     * @var string
     */
    protected $sites;

    /**
     * Site names.
     *
     * @var array
     */
    protected $site_names;

    /**
     * Server names.
     *
     * @var Client
     */
    protected $http;

    /**
     * SaveEnvVariable constructor.
     *
     */
    public function __construct(Client $http)
    {
        parent::__construct();
        $this->http = $http;
    }

        /**
     * Env var to set.
     *
     * @return mixed
     */
    protected function envVar()
    {
        return 'ACACHA_FORGE_SITE';
    }

    /**
     * Argument key.
     *
     * @return mixed
     */
    protected function argKey()
    {
        return 'site';
    }

    /**
     * Question text.
     *
     * @return mixed
     */
    protected function questionText()
    {
        return 'Acacha forge site id?';
    }

    /**
     * Before hook.
     */
    protected function before()
    {
        $this->sites = $this->fetchSites(fp_env('ACACHA_FORGE_SERVER'));
        $this->site_names = collect($this->sites)->pluck('name')->toArray();
    }

    /**
     * Default proposed value when asking.
     *
     */
    protected function default() {
        $current_value = fp_env('ACACHA_FORGE_SITE');
        return $current_value ? $this->getSiteName($this->sites, $current_value) : fp_env('ACACHA_FORGE_DOMAIN');
    }

    /**
     * Value.
     */
    protected function value()
    {
        $site_name = $this->anticipate( $this->questionText(), $this->site_names, $this->default());
        return $this->getSiteId($this->sites, $site_name);
    }

}
