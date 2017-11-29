<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\RunsSSHCommands;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishZsh.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishZsh extends Command
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
    protected $signature = 'publish:zsh {--server=} {--domain=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install zsh and oh my zsh';

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
        $this->info("Installing zsh on production...");
        $this->runSSH('cd $this->domain;sudo apt-get install zsh;sudo apt-get install curl;sh -c "$(curl -fsSL https://raw.github.com/robbyrussell/oh-my-zsh/master/tools/install.sh)"');
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
