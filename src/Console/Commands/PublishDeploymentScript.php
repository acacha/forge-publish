<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\DiesIfEnvVariableIsnotInstalled;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Class PublishDeploymentScript.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishDeploymentScript extends Command
{

    use ChecksEnv, DiesIfEnvVariableIsnotInstalled;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:deployment_script {file?} {--server=} {--site=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deployment script on Laravel Forge site';

    /**
     * Server forge id.
     *
     * @var string
     */
    protected $server;

    /**
     * Laravel forge site id.
     *
     * @var string
     */
    protected $site;


    /**
     * File with new script content.
     *
     * @var string
     */
    protected $file;

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

        if ($this->file = $this->argument('file')) {
            $this->updateDeploymentScript();
        } else {
            $this->showDeploymentScript();
        }
    }

    protected function showDeploymentScript()
    {
        $this->info('Deployment script for site ' . $this->site . ':');

        $this->url = $this->obtainAPIURLEndpoint('show_deployment_script_uri');

        $script = $this->http->get($this->url, [
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . fp_env('ACACHA_FORGE_ACCESS_TOKEN')
                ]
            ]
        );

        $this->info('');
        $this->line($script->getBody()->getContents());
    }

    /**
     * Update deployment script.
     */
    protected function updateDeploymentScript()
    {
        $this->info('Updating deployment script for site ' . $this->site . '...');

        $this->url = $this->obtainAPIURLEndpoint('update_deployment_script_uri');

        if ( ! File::exists($this->file)) {
            $this->error("File " . $this->file . " doesn't exists");
            die();
        }

        $this->http->put($this->url, [
                'form_params' => [
                    'content' => file_get_contents($this->file)
                ],
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . fp_env('ACACHA_FORGE_ACCESS_TOKEN')
                ]
            ]
        );
    }

    /**
     * Obtain API URL endpoint.
     *
     * @return string
     */
    protected function obtainAPIURLEndpoint($type)
    {
        $uri = str_replace('{forgeserver}', $this->server , config('forge-publish.' . $type));
        $uri = str_replace('{forgesite}', $this->site , $uri);
        return config('forge-publish.url') . $uri;
    }

    /**
     * Abort command execution.
     */
    protected function abortCommandExecution()
    {
        $this->server = $this->checkEnv('server','ACACHA_FORGE_SERVER');
        $this->site = $this->checkEnv('site','ACACHA_FORGE_SITE');
        $this->dieIfEnvVarIsNotInstalled('ACACHA_FORGE_ACCESS_TOKEN');
    }
}
