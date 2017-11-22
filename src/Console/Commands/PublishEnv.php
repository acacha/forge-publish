<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\ChecksSSHConnection;
use Acacha\ForgePublish\Commands\Traits\RunsSSHCommands;
use Illuminate\Console\Command;

/**
 * Class PublishEnv.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishEnv extends Command
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
    protected $signature = 'publish:env {action?} {key?} {value?} {--server=} {--domain=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage environment on production';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->abortCommandExecution();
        if (! $this->argument('action') || $this->argument('action') == 'list' ) {
            $this->listEnvVariables();
            return;
        }

        if ( $this->argument('action') == 'edit' ) {
            $this->editEnvVariables();
            return;
        }

        if ( $this->argument('action') == 'check' ) {
            $this->checkEnvVariable();
            return;
        }

    }

    /**
     * List remote env variables
     */
    protected function listEnvVariables()
    {
        $this->runSSH($this->server, "cd $this->domain;cat .env");
    }

    /**
     * Edit remote env variables
     */
    protected function editEnvVariables()
    {
        $this->runSSH($this->server, "cd $this->domain;editor .env");
    }

    /**
     * Edit remote env variables
     */
    protected function checkEnvVariable()
    {
        $key = $this->checkKey();
        $output = $this->execSSH($this->server, "cd $this->domain;cat .env");
        if (str_contains($output,$key)) {
            $this->info("Key $key found in remote environment file");
        } else {
            $this->error("Key $key NOT found in remote environment file");
        }
    }

    /**
     * Check key.
     *
     * @return array|string
     */
    protected function checkKey()
    {
        if (! $key = $this->argument('key')) {
            $this->error('No key argument has been provided!');
            die();
        }
        return $key;
    }

    /**
     * Check value.
     * @return array|string
     */
    protected function checkValue()
    {
        if (! $value = $this->argument('value')) {
            $this->error('No value argument has been provided!');
            die();
        }
        return $value;
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
