<?php

namespace Acacha\ForgePublish\Commands;

use Illuminate\Console\Command;


/**
 * Class PublishInfo.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishInfo extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show Acacha Forge info';

    /**
     * Create a new command instance.
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
        $this->info('Here is your configuration...');

        $headers = ['Key', 'Value'];

        $tasks = [
            [ 'ACACHA_FORGE_URL', env('ACACHA_FORGE_URL','Not available!')],
            [ 'ACACHA_FORGE_ACCESS_TOKEN', env('ACACHA_FORGE_ACCESS_TOKEN',null) ? 'Ok!' : 'Not available!'],
            [ 'ACACHA_FORGE_EMAIL', env('ACACHA_FORGE_EMAIL','Not available!')],
            [ 'ACACHA_FORGE_SERVER', env('ACACHA_FORGE_SERVER','Not available!')],
            [ 'ACACHA_FORGE_DOMAIN', env('ACACHA_FORGE_DOMAIN','Not available!')],
            [ 'ACACHA_FORGE_SITE', env('ACACHA_FORGE_SITE','Not available!')],
        ];

        $this->table($headers, $tasks);

    }


}
