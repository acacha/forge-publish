<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\DiesIfEnvVariableIsnotInstalled;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishSSL.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishSSL extends Command
{

    use ChecksEnv, DiesIfEnvVariableIsnotInstalled;

    /**
     * Server name
     *
     * @var String
     */
    protected $server;

    /**
     * Domain
     *
     * @var String
     */
    protected $domain;

    /**
     * Forge site id.
     *
     * @var String
     */
    protected $site;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:ssl {--server=} {--domain=} {--site=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtain lets encrypt certificate';

    /**
     * API endpoint URL
     *
     * @var string
     */
    protected $url;

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
        $this->info("Obtaining Lets Encrypt Certificate on production...");

        $this->url = $this->obtainAPIURLEndpoint();

        
        $this->http->post($this->url,
            [
                'form_params' => [
                    'domains' => [$this->domain]
                ],
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
        $uri = str_replace('{forgeserver}', $this->server , config('forge-publish.post_lets_encrypt_uri'));
        $uri = str_replace('{forgesite}', $this->site , $uri);
        return config('forge-publish.url') . $uri;
    }

    /**
     * Abort command execution?
     */
    protected function abortCommandExecution()
    {
        $this->server = $this->checkEnv('server','ACACHA_FORGE_SERVER');
        $this->domain = $this->checkEnv('domain','ACACHA_FORGE_DOMAIN');
        $this->site = $this->checkEnv('site','ACACHA_FORGE_SITE');
        $this->dieIfEnvVarIsNotInstalled('ACACHA_FORGE_ACCESS_TOKEN');
    }

}
