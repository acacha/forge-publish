<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\RunsSSHCommands;
use Acacha\ForgePublish\Parser\ForgePublishRCParser;
use Illuminate\Console\Command;

/**
 * Class PublishLog.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishLog extends Command
{
    use ChecksEnv,RunsSSHCommands;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:log {--server=} {--domain=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show logs on server (or localhost)';

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
     * Create a new command instance.
     *
     */
    public function __construct(ForgePublishRCParser $parser)
    {
        parent::__construct();
        $this->parser = $parser;
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->abortCommandExecution();

        $this->info("Connecting to server $this->server to see logs");
        $this->runSSH($this->server, "tail -f $this->domain/storage/logs/laravel.log");
    }

    /**
     * Abort command execution.
     *
     */
    protected function abortCommandExecution() {
        $this->server = $this->checkEnv('server','ACACHA_FORGE_SERVER');
        $this->domain = $this->checkEnv('domain','ACACHA_FORGE_DOMAIN');
    }

}
