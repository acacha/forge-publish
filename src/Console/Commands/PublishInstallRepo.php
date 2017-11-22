<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\ChecksSSHConnection;
use Acacha\ForgePublish\Commands\Traits\RunsSSHCommands;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishInstallRepo.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishInstallRepo extends Command
{

    use ChecksEnv;

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
     * Token
     *
     * @var String
     */
    protected $token;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:install_repo {repository?} {--server=} {--site=}{--token=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install repo on Laravel Forge site';

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
                    'Authorization' => 'Bearer ' . $this->token
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
        $this->token = $this->checkEnv('token','ACACHA_FORGE_ACCESS_TOKEN');
    }

}
