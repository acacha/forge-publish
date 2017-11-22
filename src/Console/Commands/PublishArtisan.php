<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\ChecksSSHConnection;
use Acacha\ForgePublish\Commands\Traits\RunsSSHCommands;
use Illuminate\Console\Command;

/**
 * Class PublishArtisan.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishArtisan extends Command
{

    use ChecksSSHConnection, ChecksEnv, RunsSSHCommands;

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
    protected $signature = 'publish:artisan {artisan_command?} {--server=} {--domain=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run artisan on production server';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->abortCommandExecution();
        $command = $this->argument('artisan_command');
        $this->info("Running php artisan $command on production...");
        $this->runSSH($this->server,"cd $this->domain;php artisan $command");
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
