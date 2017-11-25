<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\ChecksToken;

use Acacha\ForgePublish\Commands\Traits\DiesIfNoEnvFileExists;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishCheckToken.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishCheckToken extends Command
{
    use DiesIfNoEnvFileExists, ChecksEnv, ChecksToken;

    /**
     * API Access Token.
     */
    protected $token;

    /**
     * API endpoint URL.
     */
    protected $url;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:check_token {token?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check token is valid';

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
        $this->abortCommandExecution();

        $this->info("Checking if token is valid...");

        $this->url = $this->checkTokenURL();

        if ($this->checkToken($this->token)) {
            $this->info('Token is valid!');
        } else {
            $this->error('Token not valid!');
        }
    }

    /**
     * Abort command execution.
     *
     */
    protected function abortCommandExecution() {
        if ( ! $this->argument('token') ) $this->dieIfNoEnvFileIsFound();
        $this->token = $this->checkEnv('token','ACACHA_FORGE_ACCESS_TOKEN','argument');    }

}
