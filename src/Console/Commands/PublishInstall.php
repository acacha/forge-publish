<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\ChecksSSHConnection;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishInstall.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishInstall extends Command
{
    use ChecksEnv,ChecksSSHConnection;

    /**
     * Server name
     *
     * @var String
     */
    protected $server;

    /**
     * Domain
     *
     * @var String
     */
    protected $domain;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:install {--server=} {--domain=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install project into production Laravel Forge server';

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
        $this->info("I'm going to install this project to production...");

        if ($this->confirm('Do you have ignored local files you want to add to production?')) {
            $this->call('publish:ignored');
        }

        if ($this->confirm('Do you have Github projects ignored in local do you want to add to production?')) {
            $this->call('publish:git_dependencies');
        }

        $this->call('publish:composer', [
            'composer_command' => 'install'
        ]);

        $this->call('publish:npm', [
            'npm_command' => 'install',
        ]);

        $this->call('publish:key_generate');

        if ($this->confirm('Do you wish run migrations on production?')) {
            $this->call('publish:artisan',[
                'artisan_command' => 'migrate --force',
            ]);
        }
    }

    /**
     * Abort command execution?
     */
    protected function abortCommandExecution()
    {
        $this->server = $this->checkEnv('server','ACACHA_FORGE_SERVER');
        $this->domain = $this->checkEnv('domain','ACACHA_FORGE_DOMAIN');

        $this->abortIfNoSSHConnection();
    }

}
