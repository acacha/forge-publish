<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksServer;
use Acacha\ForgePublish\Commands\Traits\ChecksSite;
use Acacha\ForgePublish\Commands\Traits\ChecksToken;
use GuzzleHttp\Client;
use Illuminate\Console\Command;


/**
 * Class PublishInfo.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishInfo extends Command
{
    use ChecksToken, ChecksServer, ChecksSite;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show Acacha Forge info';

    /**
     * Guzzle Http client
     *
     * @var Client
     */
    protected $http;

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
        $this->info('Here is your configuration:');

        $headers = ['Key', 'Value'];

        $tasks = [
            [ 'ACACHA_FORGE_URL', fp_env('ACACHA_FORGE_URL','Not available!')],
            [ 'ACACHA_FORGE_ACCESS_TOKEN', $this->token() ],
            [ 'ACACHA_FORGE_EMAIL', fp_env('ACACHA_FORGE_EMAIL','Not available!')],
            [ 'ACACHA_FORGE_SERVER', $this->server()],
            [ 'ACACHA_FORGE_DOMAIN', fp_env('ACACHA_FORGE_DOMAIN','Not available!')],
            [ 'ACACHA_FORGE_PROJECT_TYPE', fp_env('ACACHA_FORGE_PROJECT_TYPE','Not available!')],
            [ 'ACACHA_FORGE_SITE', $this->site()],
            [ 'ACACHA_FORGE_SITE_DIRECTORY', fp_env('ACACHA_FORGE_SITE_DIRECTORY','Not available!')],
            [ 'ACACHA_FORGE_IP_ADDRESS', fp_env('ACACHA_FORGE_IP_ADDRESS','Not available!')],
            [ 'ACACHA_FORGE_GITHUB_REPO', fp_env('ACACHA_FORGE_GITHUB_REPO','Not available!')]
        ];

        $this->table($headers, $tasks);

    }

    /**
     * Check and obtain token state.
     */
    protected function token() {
        if ($token = fp_env('ACACHA_FORGE_ACCESS_TOKEN',null)) {
            if ($this->checkToken($token)) return 'OK!';
            $this->error('Be careful! The token you provided is not valid (unauthorized!)');
            return '<error>Available but not correct!</error>';
        } else {
          return 'Not available!';
        }
    }

    /**
     * Check and obtain server Forge id.
     *
     * @return null
     */
    protected function server()
    {
        if ($server = fp_env('ACACHA_FORGE_SERVER',null)) {
            if ($this->checkServer()) return $server;
            $this->error('Be careful! The server you provided is not valid');
            return "<error>$server</error>";
        } else {
            return 'Not available!';
        }
    }

    /**
     * Check and obtain site Forge id.
     *
     * @return null
     */
    protected function site()
    {
        if ($site = fp_env('ACACHA_FORGE_SITE',null)) {
            if ($this->checkSite()) return $site;
            $this->error('Be careful! The site you provided is not valid');
            return "<error>$site</error>";
        } else {
            return 'Not available!';
        }
    }
}
