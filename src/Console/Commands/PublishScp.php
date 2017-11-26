<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\RunsSSHCommands;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishScp.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishScp extends Command
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
    protected $signature = 'publish:scp {file} {--server=} {--domain=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy file or folder to production via scp';

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
        $file = $this->argument('file');
        $this->runScp(base_path($file),$this->domain,null,true);
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
