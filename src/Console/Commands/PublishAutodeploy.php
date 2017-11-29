<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\DiesIfEnvVariableIsnotInstalled;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishAutodeploy.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishAutodeploy extends Command
{
    use ChecksEnv, DiesIfEnvVariableIsnotInstalled;

    /**
     * Server name
     *
     * @var String
     */
    protected $server;

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
    protected $signature = 'publish:autodeploy {--server=} {--site=} {--disable}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable Laravel Forge autodeploy';

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
        $http_method = 'post';
        $action = 'Enabling';
        $actionp = 'Enabled';
        if ($this->option('disable')) {
            $action = 'Disabling';
            $http_method = 'delete';
            $actionp = 'Disabled';
        }

        $this->info("$action autodeploy on Laravel Forge Site...");

        $uri = str_replace('{forgeserver}', $this->server, config('forge-publish.post_auto_deploy_uri'));
        $uri = str_replace('{forgesite}', $this->site, $uri);
        $url = config('forge-publish.url') . $uri;

        $this->http->$http_method($url,
            [
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . fp_env('ACACHA_FORGE_ACCESS_TOKEN')
                ]
            ]
        );

        $this->info("Autodeploy on Laravel Forge Site $actionp!");
    }

    /**
     * Abort command execution?
     */
    protected function abortCommandExecution()
    {
        $this->server = $this->checkEnv('server', 'ACACHA_FORGE_SERVER');
        $this->site = $this->checkEnv('site', 'ACACHA_FORGE_SITE');
        $this->dieIfEnvVarIsNotInstalled('ACACHA_FORGE_ACCESS_TOKEN');
    }
}
