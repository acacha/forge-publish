<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksForRootPermission;
use Acacha\ForgePublish\Commands\Traits\DNSisAlreadyConfigured;
use Acacha\ForgePublish\Commands\Traits\GetsEnv;
use Acacha\ForgePublish\Commands\Traits\ItFetchesServers;
use Acacha\ForgePublish\Commands\Traits\ItFetchesSites;
use Acacha\ForgePublish\Commands\Traits\PossibleEmails;
use Acacha\ForgePublish\ForgePublishRCFile;
use Acacha\ForgePublish\Parser\ForgePublishRCParser;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class Publish.
 *
 * @package Acacha\ForgePublish\Commands
 */
class Publish extends Command
{
    use ItFetchesSites,ItFetchesServers, PossibleEmails, ChecksForRootPermission, DNSisAlreadyConfigured, GetsEnv;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish';

    /**
     * Guzzle Http client
     *
     * @var Client
     */
    protected $http;

    /**
     * ForgePublishRCParser
     *
     * @var ForgePublishRCParser
     */
    protected $parser;

    /**
     * Is DNS already configured?
     *
     * @var Boolean
     */
    protected $dnsAlreadyConfigured = false;

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
        dump('TODO');
    }

}
