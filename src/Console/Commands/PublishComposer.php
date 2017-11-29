<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\RunsSSHCommands;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishComposer.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishComposer extends Command
{
    use ChecksEnv, RunsSSHCommands;

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
    protected $signature = 'publish:composer {composer_command} {--server=} {--domain=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run composer on production server';

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
        $command = $this->argument('composer_command');
        $this->info("Running composer $command on production...");

        $this->runSSH("cd $this->domain;composer $command");
    }

    /**
     * Abort command execution?
     */
    protected function abortCommandExecution()
    {
        $this->server = $this->checkEnv('server', 'ACACHA_FORGE_SERVER');
        $this->domain = $this->checkEnv('domain', 'ACACHA_FORGE_DOMAIN');

        $this->abortIfNoSSHConnection();
    }
}
