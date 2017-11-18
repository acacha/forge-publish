<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ItFetchesServers;
use Acacha\ForgePublish\Commands\Traits\ShowsErrorResponse;
use Acacha\ForgePublish\Commands\Traits\SkipsIfEnvVariableIsnotInstalled;
use Acacha\ForgePublish\Commands\Traits\SkipsIfNoEnvFileExists;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishCreateSite.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishCreateSite extends Command
{
    use ShowsErrorResponse, SkipsIfNoEnvFileExists, SkipsIfEnvVariableIsnotInstalled, ItFetchesServers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:create_site {forge_server?} {domain?} {project_type?} {site_directory?} {--token=}\'';

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
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->checkIfCommandHaveToBeSkipped();

        $servers = $this->option('token') ? $this->fetchServers($this->option('token')) : $this->fetchServers();
        $server_names = collect($servers)->pluck('name')->toArray();

        if ($this->argument('forge_server')) {
            $forge_server = $this->argument('forge_server');
        } else {
            $server_name = $this->choice('Forge server?', $server_names, 0);
            $forge_server = $this->getForgeIdServer($servers,$server_name);
        }

        $domain = $this->argument('domain') ? $this->argument('domain') : $this->ask('Domain?');
        $project_type = $this->argument('project_type') ?
                    $this->argument('project_type') :
                    $this->ask('Project Type?', config('forge-publish.project_type'));
        $site_directory = $this->argument('site_directory') ?
            $this->argument('site_directory') :
            $this->ask('Directory?', config('forge-publish.site_directory'));

        $uri = str_replace('{forgeserver}', $forge_server , config('forge-publish.post_sites_uri'));
        $this->url = config('forge-publish.url') . $uri;

        $token = $this->option('token') ? $this->option('token'): env('ACACHA_FORGE_ACCESS_TOKEN');

        try {
            $this->http->post($this->url, [
                'form_params' => [
                    'domain' => $domain,
                    'project_type' => $project_type,
                    'directory' => $site_directory
                ],
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . $token
                ]
            ]);
        } catch (\Exception $e) {
            $this->showErrorAndDie($e);
        }

        $this->info('The site has been added to Forge');
    }

    /**
     * Check if command have to be skipped.
     */
    protected function checkIfCommandHaveToBeSkipped()
    {
        $this->skipIfNoEnvFileIsFound();
        if ( ! $this->option('token')) $this->skipIfEnvVarIsNotInstalled('ACACHA_FORGE_ACCESS_TOKEN');
    }

}
