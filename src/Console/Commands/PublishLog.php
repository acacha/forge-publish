<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Parser\ForgePublishRCParser;
use Illuminate\Console\Command;

/**
 * Class PublishLog.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishLog extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:log';

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
    protected $forge_server;

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

        $this->info("Connecting to server $this->forge_server to see logs");
        $this->info('ssh ' . $this->forge_server . " tail -f $this->domain/storage/logs/laravel.log");
        passthru('ssh ' . $this->forge_server . " tail -f $this->domain/storage/logs/laravel.log");
    }

    /**
     * Abort command execution.
     *
     */
    protected function abortCommandExecution() {
        $this->forge_server = env('ACACHA_FORGE_SERVER', null);
        if (!$this->forge_server) {
            $this->error('No env variable ACACHA_FORGE_SERVER found. Please run php artisan publish:init');
            die();
        }
        $this->domain = env('ACACHA_FORGE_DOMAIN', null);
        if (! $this->domain ) {
            $this->error('No env variable ACACHA_FORGE_DOMAIN found. Please run php artisan publish:init');
            die();
        }
    }


}
