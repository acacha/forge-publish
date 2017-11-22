<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\ChecksSSHConnection;
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
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->abortCommandExecution();
        $this->info("I'm going to install this project to production...");

        $this->call('publish:composer', [
            'composer_command' => 'install',
            '--server' => $this->server,
            '--domain' => $this->domain
        ]);

        $this->call('publish:npm', [
            'npm_command' => 'install',
            '--server' => $this->server,
            '--domain' => $this->domain
        ]);

        $this->call('publish:key_generate',[
            '--server' => $this->server,
            '--domain' => $this->domain
        ]);

        if ($this->confirm('Do you wish run migrations on production?')) {
            $this->call('publish:artisan',[
                'artisan_command' => 'migrate --force',
                '--server' => $this->server,
                '--domain' => $this->domain
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

        $this->abortIfNoSSHConnection($this->server);
    }

}
