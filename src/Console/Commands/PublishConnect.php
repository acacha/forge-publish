<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\RunsSSHCommands;
use Acacha\ForgePublish\Parser\ForgePublishRCParser;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishConnect.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishConnect extends Command
{
    use RunsSSHCommands, ChecksEnv;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:connect {--server=} {--domain=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Connect to server via SSH';

    /**
     * ForgePublishRCParser
     *
     * @var ForgePublishRCParser
     */
    protected $parser;

    /**
     * Forge server.
     *
     * @var String
     */
    protected $server;

    /**
     * Domain.
     *
     * @var String
     */
    protected $domain;

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
    public function __construct(Client $http, ForgePublishRCParser $parser)
    {
        parent::__construct();
        $this->http = $http;
        $this->parser = $parser;
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->abortCommandExecution();
        $this->info("Connecting to server $this->server");
        $this->runSSH("cd $this->domain;" . $this->defaultShell());
    }

    /**
     * Abort command execution.
     *
     */
    protected function abortCommandExecution()
    {
        $this->server = $this->checkEnv('server', 'ACACHA_FORGE_SERVER');
        $this->domain = $this->checkEnv('domain', 'ACACHA_FORGE_DOMAIN');
    }

    /**
     * Default Shell.
     *
     * @return string
     */
    protected function defaultShell()
    {
        if ($this->parser->getDefaultShell() == '') {
            return 'bash';
        }
        return $this->parser->getDefaultShell();
    }
}
