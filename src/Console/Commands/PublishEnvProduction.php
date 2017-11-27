<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\ChecksSSHConnection;
use Acacha\ForgePublish\Commands\Traits\RunsSSHCommands;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Class PublishEnvProduction.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishEnvProduction extends Command
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
     * Local path.
     *
     * @var String
     */
    protected $localPath;

    /**
     * Remote path.
     *
     * @var String
     */
    protected $remotePath;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:env-production {--server=} {--domain=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install local .env.production to .env in production';

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
        $this->remotePath = $this->domain . '/.env';
        $this->runScp($this->localPath, $this->remotePath, null, true);
    }

    /**
     * Abort command execution?
     */
    protected function abortCommandExecution()
    {

        $this->server = $this->checkEnv('server','ACACHA_FORGE_SERVER');
        $this->domain = $this->checkEnv('domain','ACACHA_FORGE_DOMAIN');

        if ( ! File::exists($this->localPath = base_path('.env.production'))) {
            $this->error('File ' . $this->localPath . ' not found!');
            die();
        }

        $this->abortIfNoSSHConnection();
    }
}
