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
        $this->info('Here is your configuration:');

        $headers = ['Key', 'Value'];

        $tasks = [
            [ 'ACACHA_FORGE_URL', fp_env('ACACHA_FORGE_URL','Not available!')],
            [ 'ACACHA_FORGE_ACCESS_TOKEN', fp_env('ACACHA_FORGE_ACCESS_TOKEN',null) ? 'Ok!' : 'Not available!'],
            [ 'ACACHA_FORGE_EMAIL', fp_env('ACACHA_FORGE_EMAIL','Not available!')],
            [ 'ACACHA_FORGE_SERVER', fp_env('ACACHA_FORGE_SERVER','Not available!')],
            [ 'ACACHA_FORGE_DOMAIN', fp_env('ACACHA_FORGE_DOMAIN','Not available!')],
            [ 'ACACHA_FORGE_PROJECT_TYPE', fp_env('ACACHA_FORGE_PROJECT_TYPE','Not available!')],
            [ 'ACACHA_FORGE_SITE', fp_env('ACACHA_FORGE_SITE','Not available!')],
            [ 'ACACHA_FORGE_SITE_DIRECTORY', fp_env('ACACHA_FORGE_SITE_DIRECTORY','Not available!')],
            [ 'ACACHA_FORGE_IP_ADDRESS', fp_env('ACACHA_FORGE_IP_ADDRESS','Not available!')],
            [ 'ACACHA_FORGE_GITHUB_REPO', fp_env('ACACHA_FORGE_GITHUB_REPO','Not available!')]
        ];

        $this->table($headers, $tasks);

    }
}
