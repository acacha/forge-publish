<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ShowsErrorResponse;
use Acacha\ForgePublish\Commands\Traits\SkipsIfNoEnvFileExists;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishSite.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishSite extends Command
{
    use ShowsErrorResponse, SkipsIfNoEnvFileExists;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:site';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create site on forge for the current user';

    /**
     * Guzzle Http client
     *
     * @var Client
     */
    protected $http;

    /**
     * API endpoint URL
     *
     * @var String
     */
    protected $url;

    /**
     * PublishSite constructor.
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
     * @return mixed
     */
    public function handle()
    {
        $this->checkIfCommandHaveToBeSkipped();

        $email = $this->ask('What is your email(username)?');
        $password = $this->secret('What is the password?');

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

        dd(json_decode( (string) $response->getBody()));

        $access_token = json_decode( (string) $response->getBody())->access_token ;

//        $this->addValueToEnv('ACACHA_FORGE_ACCESS_TOKEN', $access_token);

        $this->info('The site has been added to Forge');
    }

    /**
     * Check if command have to be skipped.
     */
    protected function checkIfCommandHaveToBeSkipped()
    {
        $this->skipIfNoEnvFileIsFound();
        $this->skipIfTokenNotExists();
    }

    /**
     * Skip if token not exists.
     */
    protected function skipIfTokenNotExists()
    {
        $environment = $this->loadEnv();
        if ( ! array_key_exists( 'ACACHA_FORGE_ACCESS_TOKEN' , $environment)) {
            $this->info('No ACACHA_FORGE_ACCESS_TOKEN key found in .env file.');
            $this->info('Please login using php artisan publish:login command.');
            $this->info('Skipping...');
            die();
        }
    }

}
