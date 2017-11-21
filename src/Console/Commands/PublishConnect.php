<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Compiler\RCFileCompiler;
use Acacha\ForgePublish\ForgePublishRCFile;
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

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:connect';

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
        $forge_server = env('ACACHA_FORGE_SERVER', null);
        if ( ! $forge_server ) {
            $this->error('No env variable ACACHA_FORGE_SERVER found. Please run php artisan publish:init');
        }
        $this->info("Connecting to server $forge_server");
        $domain = env('ACACHA_FORGE_DOMAIN', null);
        if ( ! $domain ) {
            passthru('ssh ' . $forge_server);
        } else {
            $this->info('ssh -t ' . $forge_server . ' "cd ' . $domain . ';' . $this->defaultShell() .'"');
            passthru('ssh -t ' . $forge_server . ' "cd ' . $domain . ';' . $this->defaultShell() .'"');
        }
    }

    /**
     * Default Shell.
     *
     * @return string
     */
    protected function defaultShell()
    {
        if ($this->parser->getDefaultShell() == '') return 'bash';
        return $this->parser->getDefaultShell();
    }

}
