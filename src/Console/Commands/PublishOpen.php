<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Illuminate\Console\Command;

/**
 * Class PublishOpen.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishOpen extends Command
{
    use ChecksEnv;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:open {--domain=}';

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
        $this->domain = $this->checkEnv('domain', 'ACACHA_FORGE_DOMAIN');
    }
}
