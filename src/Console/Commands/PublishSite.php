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
    protected $description = 'Save AcachaForge site';

    /**
     * Sites.
     *
     * @var string
     */
    protected $sites;

    /**
     * Default value.
     *
     * @var string
     */
    protected $default;

    /**
     * Site names.
     *
     * @var array
     */
    protected $site_names;

    /**
     * Site already exists?.
     *
     * @var boolean
     */
    protected $site_already_exists = false;

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
        $this->default = $this->default();
    }

    /**
     * Default proposed value when asking.
     *
     */
    protected function default() {
        $current_value = fp_env('ACACHA_FORGE_SITE');
        if ( $current_value ) {
            $site_name = $this->getSiteName($this->sites, $current_value);
            if ($site_name) return $site_name;
        }
        return fp_env('ACACHA_FORGE_DOMAIN');
    }

    /**
     * Value.
     */
    protected function value()
    {
        $site_name = $this->anticipate( $this->questionText(), $this->site_names, $this->default);
        $site_id = $this->getSiteId($this->sites, $site_name);
        if ( ! $site_id ) {
            $this->call('publish:create_site');
        } else {
            $this->info("Site ($site_name) already exists on Laravel Forge with site_id: $site_id");
        }
        $site_id = $this->getSiteId($this->sites, $site_name);
        if(!$site_id) {
            $this->error("No site_id obtained! Maybe some error occurs when creating site on Laravel Forge...");
            die();
        }
        return $site_id;
    }

}
