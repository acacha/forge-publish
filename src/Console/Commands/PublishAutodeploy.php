<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\ChecksSSHConnection;
use Acacha\ForgePublish\Commands\Traits\RunsSSHCommands;
use Illuminate\Console\Command;

/**
 * Class PublishAutodeploy.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishAutodeploy extends Command
{

    use ChecksEnv;

    /**
     * Server name
     *
     * @var String
     */
    protected $server;

    /**
     * Forge site id.
     *
     * @var String
     */
    protected $site;

    /**
     * Token
     *
     * @var String
     */
    protected $token;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:autodeploy {--server=} {--site=} {--token=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable Laravel Forge autodeploy';

    /**
     * Constructor.
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
        $this->abortCommandExecution();
        $this->info("Enablind autodeploy on Laravel Forge Site...");

        $uri = str_replace('{forgeserver}', $this->server , config('forge-publish.post_auto_deploy_uri'));
        $uri = str_replace('{forgesite}', $this->site , $uri);
        $url = config('forge-publish.url') . $uri;
        $response = $this->http->post($url,
            [
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]
        );

        $result = json_decode($contents = $response->getBody()->getContents());

        //TODO
        dump($result);
    }

    /**
     * Abort command execution?
     */
    protected function abortCommandExecution()
    {
      $this->server = $this->checkEnv('server','ACACHA_FORGE_SERVER');
      $this->site = $this->checkEnv('site','ACACHA_FORGE_SITE');
      $this->token = $this->checkEnv('token','ACACHA_FORGE_ACCESS_TOKEN');
    }

}
