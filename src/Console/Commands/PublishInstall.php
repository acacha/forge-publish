<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Compiler\RCFileCompiler;
use Acacha\ForgePublish\ForgePublishRCFile;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishInstall.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishInstall extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:install {domain_suffix?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install project to production';

    /**
     * Guzzle Http client
     *
     * @var Client
     */
    protected $http;

    /**
     * Constructor.
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
        $this->info("I'm going to install this project to production...");

        // TODO STEPS
        // 1) Install repository (api don't run composer install) in some cases better to avoid problems with studio/local packages
        // 2) Enable quick deployment https://forge.laravel.com/api-documentation#deployment
        // POST /api/v1/servers/{serverId}/sites/{siteId}/deployment




//        $site->installGitRepository(array $data);

//        POST /api/v1/servers/{serverId}/sites/{siteId}/git
//
//          "provider": "github",
//          "repository": "username/repository",
//          "branch": "master"
//}


    }

}
