<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\DiesIfEnvVariableIsnotInstalled;
use Acacha\ForgePublish\Commands\Traits\WaitsForMySQLDatabase;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishMySQL.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishMySQL extends Command
{

    use ChecksEnv, DiesIfEnvVariableIsnotInstalled, WaitsForMySQLDatabase;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:mysql {name?} {user?} {password?} {--server=} {--dump} {--wait}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage MySQL databases';

    /**
     * Server forge id.
     *
     * @var string
     */
    protected $server;

    /**
     * API endpoint URL.
     *
     * @var string
     */
    protected $url;

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
        try {
            $this->http->post($this->url, [
                    'form_params' => $data = $this->getData(),
                    'headers' => [
                        'X-Requested-With' => 'XMLHttpRequest',
                        'Authorization' => 'Bearer ' . fp_env('ACACHA_FORGE_ACCESS_TOKEN')
                    ]
                ]
            );
            if ($this->option('wait')) {
                $this->info('Waiting for database to be installed in Laravel Forge server...');
                $this->waitForMySQLDatabaseByName($data['name']);
                $this->info('Installed!');
            }
        } catch(\GuzzleHttp\Exception\ServerException $se) {
            if( str_contains($se->getResponse()->getBody()->getContents(), 'The given data failed to pass validation') ) {
                $this->error('Skipping installation. Some of the data you provided already exists in server');
            }
        } catch (\Exception $e) {
            dump($e->getMessage());
        }
    }

    /**
     * List MySQL databases.
     */
    protected function listMySQLDatabases() {

        $databases = $this->fetchMySQLDatabases();

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
     * Abort command execution.
     */
    protected function abortCommandExecution()
    {
        $this->server = $this->checkEnv('server','ACACHA_FORGE_SERVER');
        $this->dieIfEnvVarIsNotInstalled('ACACHA_FORGE_ACCESS_TOKEN');
    }
}
