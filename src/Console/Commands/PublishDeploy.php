<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\DiesIfEnvVariableIsnotInstalled;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishDeploy.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishDeploy extends Command
{

    use ChecksEnv, DiesIfEnvVariableIsnotInstalled;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:deploy {--server=} {--site=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy site';

    /**
     * API endpint URL.
     *
     * @var string
     */
    protected $url;

    /**
     * Server forge id.
     *
     * @var string
     */
    protected $server;

    /**
     * Laravel forge site id.
     *
     * @var string
     */
    protected $site;

    /**
     * Guzzle http client.
     *
     * @var Client
     */
    protected $http;

    /**
     * Create a new command instance.
     *
     */
    public function __construct(Client $http)
    {
        parent::__construct();
        $this->http = $http;
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->abortCommandExecution();

        $this->info('Executing deploy script for site ' . $this->site . ' in Laravel Forge');

        $this->url = $this->obtainAPIURLEndpoint();

        $this->http->post($this->url, [
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . fp_env('ACACHA_FORGE_ACCESS_TOKEN')
                ]
            ]
        );

    }

    /**
     * Obtain API URL endpoint.
     *
     * @return string
     */
    protected function obtainAPIURLEndpoint()
    {
        $uri = str_replace('{forgeserver}', $this->server , config('forge-publish.post_deploy_site_uri'));
        $uri = str_replace('{forgesite}', $this->site , $uri);
        return config('forge-publish.url') . $uri;
    }

    /**
     * Abort command execution.
     */
    protected function abortCommandExecution()
    {
        $this->server = $this->checkEnv('server','ACACHA_FORGE_SERVER');
        $this->site = $this->checkEnv('site','ACACHA_FORGE_SITE');
        $this->dieIfEnvVarIsNotInstalled('ACACHA_FORGE_ACCESS_TOKEN');
    }
}
