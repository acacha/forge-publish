<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\ChecksServer;
use Acacha\ForgePublish\Commands\Traits\ChecksSite;
use Acacha\ForgePublish\Commands\Traits\DiesIfEnvVariableIsnotInstalled;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishInstallRepo.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishRepository extends Command
{

    use ChecksEnv, DiesIfEnvVariableIsnotInstalled, ChecksServer, ChecksSite;

    /**
     * Github Repository.
     *
     * @var String
     */
    protected $repository;

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
    protected $signature = 'publish:repository {repository?} {--server=} {--site=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install repository on Laravel Forge site';

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
        $this->info("Installing Github repository on Laravel Forge Site...");

        $uri = str_replace('{forgeserver}', $this->server , config('forge-publish.post_git_repository_uri'));
        $uri = str_replace('{forgesite}', $this->site , $uri);
        $url = config('forge-publish.url') . $uri;

        $this->http->post($url,
            [
                'form_params' => [
                    'repository' => $this->repository
                ],
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . fp_env('ACACHA_FORGE_ACCESS_TOKEN')
                ]
            ]
        );
    }

    /**
     * Abort command execution?
     */
    protected function abortCommandExecution()
    {
        $this->repository = $this->checkEnv('repository','ACACHA_FORGE_GITHUB_REPO','argument');
        $this->server = $this->checkEnv('server','ACACHA_FORGE_SERVER');
        $this->site = $this->checkEnv('site','ACACHA_FORGE_SITE');
        $this->dieIfEnvVarIsNotInstalled('ACACHA_FORGE_ACCESS_TOKEN');

        $this->checkServerAndAbort($this->server);
        $this->checkSiteAndAbort($this->site);
    }

}
