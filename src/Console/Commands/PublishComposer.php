<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\ChecksSSHConnection;
use Acacha\ForgePublish\Commands\Traits\RunsSSHCommands;
use Illuminate\Console\Command;

/**
 * Class PublishComposer.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishComposer extends Command
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
    protected $signature = 'publish:composer {composer_command} {--server=} {--domain=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run composer on production server';

    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();
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

        dump('10 $this->server: ' . $this->server);
        dump('11 $this->domain: ' . $this->domain);
        dump('11 $command: ' . $command);
        $this->runSSH($this->server,"cd $this->domain;composer $command");
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
