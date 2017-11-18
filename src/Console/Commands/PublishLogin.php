<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\InteractsWithEnvironment;
use Acacha\ForgePublish\Commands\Traits\ShowsErrorResponse;
use Acacha\ForgePublish\Commands\Traits\SkipsIfEnvVariableIsAlreadyInstalled;
use Acacha\ForgePublish\Commands\Traits\SkipsIfNoEnvFileExists;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishLogin.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishLogin extends Command
{
    use ShowsErrorResponse, SkipsIfNoEnvFileExists, SkipsIfEnvVariableIsAlreadyInstalled, InteractsWithEnvironment;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:login {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Login to acacha forge';

    /**
     * Guzzle Http client
     *
     * @var Client
     */
    protected $http;

    /**
     * Endpoint api url.
     *
     * @var String
     */
    protected $url;

    /**
     * PublishLogin constructor.
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
        $this->checkIfCommandHaveToBeSkipped();

        $email = $this->argument('email') ? $this->argument('email') : $this->ask('Email?');
        $password = $this->secret('Password?');

        $this->url = config('forge-publish.url') . config('forge-publish.token_uri');
        $response = '';
        try {
            $response = $this->http->post($this->url, [
                'form_params' => [
                    'client_id' => config('forge-publish.client_id'),
                    'client_secret' => config('forge-publish.client_secret'),
                    'grant_type' => 'password',
                    'username' => $email,
                    'password' => $password,
                    'scope' => '*',
                ]
            ]);
        } catch (\Exception $e) {
            $this->showErrorAndDie($e);
        }

        $access_token = json_decode( (string) $response->getBody())->access_token ;

        $this->addValueToEnv('ACACHA_FORGE_ACCESS_TOKEN', $access_token);

        $this->info('The access token has been added to file .env with key ACACHA_FORGE_ACCESS_TOKEN');
    }

    /**
     * Check if command have to be skipped.
     */
    protected function checkIfCommandHaveToBeSkipped()
    {
        $this->skipIfNoEnvFileIsFound();
        $this->skipIfEnvVarIsAlreadyInstalled('ACACHA_FORGE_ACCESS_TOKEN');
    }

}
