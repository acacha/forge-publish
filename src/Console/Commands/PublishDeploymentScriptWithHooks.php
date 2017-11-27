<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\DiesIfEnvVariableIsnotInstalled;
use Acacha\ForgePublish\Compiler\RCFileCompiler;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Class PublishDeploymentScriptWithHooks.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishDeploymentScriptWithHooks extends Command
{

    use ChecksEnv, DiesIfEnvVariableIsnotInstalled;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:deployment_script_with_hooks {--domain=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install deployment script with hooks';

    /**
     * Domain.
     *
     * @var string
     */
    protected $domain;

    /**
     * Compiler for llumrc file.
     *
     * @var RCFileCompiler
     */
    protected $compiler;

    /**
     * Constructor.
     *
     */
    public function __construct(RCFileCompiler $compiler)
    {
        parent::__construct();
        $this->compiler = $compiler;
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->abortCommandExecution();
        $this->info('Installing a deployment script with hooks...');
        $data = [
            "ACACHA_FORGE_DOMAIN" => $this->domain,
            "PHP_VERSION_FPM" => 'php7.1-fpm',
        ];
        $content = $this->compiler->compile(
            file_get_contents($this->getStubPath()),
            $data);
        $tmp_file = tempnam(sys_get_temp_dir(), 'deploy_script_with_hooks');
        file_put_contents($tmp_file, $content);
        $this->call('publish:deployment_script', [
            'file' => $tmp_file
        ]);
    }

    /**
     * Get stub path.
     *
     * @return string
     */
    protected function getStubPath()
    {
        return __DIR__ . '/stubs/deployment_script_default.sh';
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
        $this->domain = $this->checkEnv('domain','ACACHA_FORGE_DOMAIN');
    }
}
