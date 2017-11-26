<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\DiesIfEnvVariableIsnotInstalled;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishMySQL.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishMySQL extends Command
{

    use ChecksEnv, DiesIfEnvVariableIsnotInstalled;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:mysql {name?} {user?} {password?} {--server=} {--dump}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage SQL databases';

    /**
     * Server forge id.
     *
     * @var string
     */
    protected $server;

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

        if ($this->argument('name')) {
            $this->createMySQLDatabase();
        } else {
            $this->listMySQLDatabases();
        }
    }

    /**
     * Create MySQL database.
     */
    protected function createMySQLDatabase()
    {
        $this->checkParameters();
        $this->url = $this->obtainAPIURLEndpoint();
        $this->http->post($this->url, [
                'form_params' => $this->getData(),
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . fp_env('ACACHA_FORGE_ACCESS_TOKEN')
                ]
            ]
        );
    }

    /**
     * List MySQL databases.
     */
    protected function listMySQLDatabases() {
        $this->url = $this->obtainAPIURLEndpointForList();
        $response = $this->http->get($this->url, [
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . fp_env('ACACHA_FORGE_ACCESS_TOKEN')
                ]
            ]
        );

        $databases = json_decode($response->getBody(),true) ;


        if ($this->option('dump')) {
            dump($databases);
        }

        if (empty($databases)) {
            $this->error('No databases found.');
            die();
        }

        $headers = ['Id', 'Server Id','Name','Status','Created at'];

        $rows = [];
        foreach ($databases as $database) {
            $rows[] = [
                $database['id'],
                $database['serverId'],
                $database['name'],
                $database['status'],
                $database['createdAt']
            ];
        }

        $this->table($headers, $rows);
    }

    /**
     * Check parameters.
     */
    protected function checkParameters()
    {
        if ($this->argument('user')) {
            if ( ! $this->argument('password')) {
                $this->error('Password argument is required if user argument is provided!');
                die();
            }
        }
    }

    /**
     * Get data.
     *
     * @return array
     */
    protected function getData()
    {
        if ($this->argument('user')) {
            return [
                'name' => $this->argument('name'),
                'user' => $this->argument('user'),
                'password' => $this->argument('password')
            ];
        } else {
            return [
                'name' => $this->argument('name')
            ];
        }
    }

    /**
     * Obtain API URL endpoint.
     *
     * @return string
     */
    protected function obtainAPIURLEndpoint()
    {
        $uri = str_replace('{forgeserver}', $this->server , config('forge-publish.post_mysql_uri'));
        return config('forge-publish.url') . $uri;
    }

    /**
     * Obtain API URL endpoint.
     *
     * @return string
     */
    protected function obtainAPIURLEndpointForList()
    {
        $uri = str_replace('{forgeserver}', $this->server , config('forge-publish.get_mysql_uri'));
        return config('forge-publish.url') . $uri;
    }

    /**
     * Abort command execution.
     */
    protected function abortCommandExecution()
    {
        $this->server = $this->checkEnv('server','ACACHA_FORGE_SERVER');
        $this->dieIfEnvVarIsNotInstalled('ACACHA_FORGE_ACCESS_TOKEN');
    }
}
