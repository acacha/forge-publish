<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
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
    use DiesIfNoEnvFileExists, ChecksEnv;

    /**
     * API Access Token.
     */
    protected $token;

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

        $this->url = $this->url();
        try {
            $response = $this->http->get($this->url, [
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]);
        } catch (\Exception $e) {
            if ($e->getResponse()) {
                if ($e->getResponse()->getStatusCode() == 401) $this->error('Authorization error. Token is not valid!');
                return;
            }
            $this->error('And error occurs validating token response!');
            return;
        }
        $content = json_decode($response->getBody()->getContents());
        if (isset($content->message)) {
            if ($content->message === 'Token is valid') $this->info('Token is valid!');
            return;
        }
        $this->error('And error occurs validating token response!');
    }

    /**
     * Get api url endpoint.
     */
    protected function url()
    {
        return config('forge-publish.url') . config('forge-publish.get_check_token_uri');
    }

    /**
     * Abort command execution.
     *
     */
    protected function abortCommandExecution() {
        if ( ! $this->argument('token') ) $this->dieIfNoEnvFileIsFound();
        $this->token = $this->checkEnv('token','ACACHA_FORGE_ACCESS_TOKEN','argument');    }

}
