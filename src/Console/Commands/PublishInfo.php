<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksServer;
use Acacha\ForgePublish\Commands\Traits\ChecksSite;
use Acacha\ForgePublish\Commands\Traits\ChecksToken;
use Acacha\ForgePublish\Commands\Traits\ItFetchesServers;
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
     * Servers
     *
     * @var array
     */
    protected $servers;

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
            [ 'ACACHA_FORGE_URL', fp_env('ACACHA_FORGE_URL', 'Not available!')],
            [ 'ACACHA_FORGE_ACCESS_TOKEN', $this->token() ],
            [ 'ACACHA_FORGE_EMAIL', fp_env('ACACHA_FORGE_EMAIL', 'Not available!')],
            [ 'ACACHA_FORGE_SERVER', $this->server()],
            [ 'ACACHA_FORGE_IP_ADDRESS', $this->ip()],
            [ 'ACACHA_FORGE_SERVER_NAME', $this->name()],
            [ 'ACACHA_FORGE_DOMAIN', fp_env('ACACHA_FORGE_DOMAIN', 'Not available!')],
            [ 'ACACHA_FORGE_PROJECT_TYPE', fp_env('ACACHA_FORGE_PROJECT_TYPE', 'Not available!')],
            [ 'ACACHA_FORGE_SITE', $this->site()],
            [ 'ACACHA_FORGE_SITE_DIRECTORY', fp_env('ACACHA_FORGE_SITE_DIRECTORY', 'Not available!')],
            [ 'ACACHA_FORGE_GITHUB_REPO', fp_env('ACACHA_FORGE_GITHUB_REPO', 'Not available!')]
        ];

        $this->table($headers, $tasks);
    }

    /**
     * Check and obtain token state.
     */
    protected function token()
    {
        if ($token = fp_env('ACACHA_FORGE_ACCESS_TOKEN', null)) {
            if ($this->checkToken($token)) {
                return 'OK!';
            }
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
        return $this->check('ACACHA_FORGE_SERVER', 'checkServer');
    }

    /**
     * Check and obtain site Forge id.
     *
     * @return null
     */
    protected function site()
    {
        return $this->check('ACACHA_FORGE_SITE', 'checkSite');
    }

    /**
     * Check and obtain ip.
     *
     * @return null
     */
    protected function ip()
    {
        return $this->check('ACACHA_FORGE_IP_ADDRESS', 'checkIp');
    }

    /**
     * Check and obtain server name.
     *
     * @return null
     */
    protected function name()
    {
        return $this->check('ACACHA_FORGE_SERVER_NAME', 'checkServerName');
    }

    /**
     * Check and obtain ip.
     *
     * @return null
     */
    protected function check($env_var, $functionName)
    {
        if ($value = fp_env($env_var, null)) {
            if ($this->$functionName()) {
                return $value;
            }
            $this->error("Be careful! The $env_var you provided doesn't match Laravel Forge server");
            return "<error>$value</error>";
        } else {
            return 'Not available!';
        }
    }
}
