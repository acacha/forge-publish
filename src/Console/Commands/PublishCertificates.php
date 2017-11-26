<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\DiesIfEnvVariableIsnotInstalled;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishCertificates.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishCertificates extends Command
{

    use ChecksEnv, DiesIfEnvVariableIsnotInstalled;

     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:certificates {--server=} {--site=} {--dump}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List SSL certificates';

    /**
     * API endpoint URL
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

        $this->url = $this->obtainAPIURLEndpoint();
        
        $response = $this->http->get($this->url, [
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . fp_env('ACACHA_FORGE_ACCESS_TOKEN')
                ]
            ]
        );

        $certificates = json_decode($response->getBody(),true) ;
        if ($this->option('dump')) {
            dump($certificates);
        }

        if (empty($certificates)) {
            $this->error('No SSL certificates found.');
            die();
        }

        $headers = ['Id', 'Domain','Request Status','Existing','Active','Created at'];

        $rows = [];
        foreach ($certificates as $certificate) {
            $rows[] = [
                $certificate['id'],
                $certificate['domain'],
                $certificate['request_status'],
                $certificate['created_at'],
                $certificate['existing'],
                $certificate['active']
            ];
        }

        $this->table($headers, $rows);
    }

    /**
     * Obtain API URL endpoint.
     *
     * @return string
     */
    protected function obtainAPIURLEndpoint()
    {
        $uri = str_replace('{forgeserver}', $this->server , config('forge-publish.get_certificates_uri'));
        $uri = str_replace('{forgesite}', $this->site , $uri);
        return config('forge-publish.url') . $uri;
    }

    /**
     * Abort command execution?
     */
    protected function abortCommandExecution()
    {
        $this->server = $this->checkEnv('server','ACACHA_FORGE_SERVER');
        $this->site = $this->checkEnv('site','ACACHA_FORGE_SITE');
        $this->dieIfEnvVarIsNotInstalled('ACACHA_FORGE_ACCESS_TOKEN');
    }

}
