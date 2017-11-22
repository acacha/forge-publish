<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\ChecksSSHConnection;
use Acacha\ForgePublish\Commands\Traits\RunsSSHCommands;
use Illuminate\Console\Command;

/**
 * Class PublishKeyGenerate.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishKeyGenerate extends Command
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
    protected $signature = 'publish:key_generate {--server=} {--domain=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run artisan key:generate command on production server';

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

        if ($this->keyIsAlreadyInstalled()) {
            $this->info('Key is already installed on production. Skipping...');
            return;
        }

        $this->call('publish:artisan', [
            'artisan_command' => 'key:generate',
            'server' => $this->server,
            'domain' => $this->domain
        ]);
    }

    /**
     * Key is already installed on production?
     *
     * @return bool
     */
    protected function keyIsAlreadyInstalled() {
        $key = 'APP_KEY=base64:';
        $output = $this->execSSH($this->server, "cd $this->domain;cat .env");
        if (str_contains($output,$key)) {
            return true;
        }
        return false;
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
