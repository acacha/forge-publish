<?php

namespace Acacha\ForgePublish\Commands;

use Illuminate\Console\Command;

/**
 * Class PublishOpen.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishOpen extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:open';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Open domain on your default (sensible-browser) browser';

    /**
     * Domain name.
     *
     * @var string
     */
    protected $domain;

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->abortCommandExecution();

        $this->info("Openning domain $this->domain");

        passthru("sensible-browser $this->domain");
    }


    /**
     * Abort command execution.
     */
    protected function abortCommandExecution()
    {
        $this->domain = env('ACACHA_FORGE_DOMAIN',null);

        if (env('ACACHA_FORGE_DOMAIN',null) == null ) {
            $this->error('No env var ACACHA_FORGE_DOMAIN found. Please run php artisan publish:init');
            die();
        }

    }


}
