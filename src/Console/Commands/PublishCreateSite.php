<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ItFetchesSites;
use Acacha\ForgePublish\Commands\Traits\ShowsErrorResponse;
use Acacha\ForgePublish\Commands\Traits\DiesIfEnvVariableIsnotInstalled;
use Acacha\ForgePublish\Commands\Traits\DiesIfNoEnvFileExists;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishCreateSite.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishCreateSite extends Command
{
    use ShowsErrorResponse, DiesIfNoEnvFileExists, DiesIfEnvVariableIsnotInstalled, ItFetchesSites;

    /**
     * Project type.
     *
     * @var String
     */
    protected $project_type;

    /**
     * Site directory.
     *
     * @var String
     */
    protected $site_directory;

    /**
     * Domain.
     *
     * @var String
     */
    protected $domain;


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:create_site {server?} {domain?} {project_type?} {site_directory?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create site on forge for the current user';

    /**
     * Guzzle Http client
     *
     * @var Client
     */
    protected $http;

    /**
     * Laravel Forge site name.
     *
     * @var array
     */
    protected $sites;

    /**
     * Laravel Forge site name.
     *
     * @var String
     */
    protected $site;

    /**
     * Laravel Forge server.
     *
     * @var String
     */
    protected $server;

    /**
     * Laravel Forge site id.
     *
     * @var integer
     */
    protected $site_id;

    /**
     * API endpoint URL
     *
     * @var String
     */
    protected $url;

    /**
     * PublishCreateSite constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        parent::__construct();
        $this->http = $client;
    }

    /**
     * Get Value.
     *
     * @return array|int|null|string
     */
    protected function getValue($value, $env_var , $command)
    {
        if ($this->argument($value)) {
            return $this->argument($value);
        } else {
            return fp_env($env_var) ? fp_env($env_var) : $this->call("publish:$command");
        }
    }

    /**
     * Get Forge server.
     *
     * @return array|int|null|string
     */
    protected function getForgeServer()
    {
        return $this->getValue('server', 'ACACHA_FORGE_SERVER' , 'server');
    }

    /**
     * Get Domain.
     *
     * @return array|int|null|string
     */
    protected function getDomain()
    {
        return $this->getValue('domain', 'ACACHA_FORGE_DOMAIN' , 'domain');
    }

    /**
     * Get project type.
     *
     * @return array|int|null|string
     */
    protected function getProjectType()
    {
        return $this->getValue('project_type', 'ACACHA_FORGE_PROJECT_TYPE' , 'project_type');
    }

    /**
     * Get site directory.
     *
     * @return array|int|null|string
     */
    protected function getSiteDirectory()
    {
        return $this->getValue('site_directory', 'ACACHA_FORGE_SITE_DIRECTORY' , 'site_directory');
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->checkIfCommandHaveToBeSkipped();
        $this->obtainFields();
        if ($this->siteIsAlreadyCreated()) {
            $this->info("Site $this->site ($this->site_id) is already created. Skipping...");
            return;
        }
        $this->url = $this->obtainApiEndPointURL();
        $this->createSiteOnForge();
        $this->info('The site has been added to Forge');
    }

    /**
     * Obtain fields.
     */
    protected function obtainFields()
    {
        $this->server = $this->getForgeServer();
        $this->domain = $this->getDomain();
        $this->project_type = $this->getProjectType();
        $this->site_directory = $this->getSiteDirectory();
    }

    /**
     * Create site on Forge.
     */
    protected function createSiteOnForge() {
        try {
            $this->info('Creating site ' . $this->domain . ' on server ' . $this->server);
            $this->http->post($this->url, [
                'form_params' => [
                    'domain' => $this->domain,
                    'project_type' => $this->project_type,
                    'directory' => $this->site_directory
                ],
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . fp_env('ACACHA_FORGE_ACCESS_TOKEN')
                ]
        ]);
        } catch (\Exception $e) {
            dump($e);
            $this->showErrorAndDie($e);
        }
    }

    /**
     * Obtain API endpoint URL.
     */
    protected function obtainApiEndPointURL() {
        $uri = str_replace('{forgeserver}', $this->server , config('forge-publish.post_sites_uri'));
        return config('forge-publish.url') . $uri;
    }

    /**
     * Check if command have to be skipped.
     */
    protected function checkIfCommandHaveToBeSkipped()
    {
        $this->dieIfNoEnvFileIsFound();
        $this->dieIfEnvVarIsNotInstalled('ACACHA_FORGE_ACCESS_TOKEN');
        $this->dieIfEnvVarIsNotInstalled('ACACHA_FORGE_SERVER');
    }

    /**
     * Is site already created?
     */
    protected function siteIsAlreadyCreated()
    {
        $this->sites = $this->fetchSites(fp_env('ACACHA_FORGE_SERVER'));
        $this->site = fp_env('ACACHA_FORGE_SITE');
        if (in_array($this->site, collect($this->sites)->pluck('id')->toArray())) return true;
        return in_array($this->domain, collect($this->sites)->pluck('name')->toArray());
    }

}
