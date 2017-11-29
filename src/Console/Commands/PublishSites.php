<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\DiesIfEnvVariableIsnotInstalled;
use Acacha\ForgePublish\Commands\Traits\ItFetchesServers;
use Acacha\ForgePublish\Commands\Traits\ItFetchesSites;
use GuzzleHttp\Client;

use Illuminate\Console\Command;

/**
 * Class PublishSites.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishSites extends Command
{
    use ItFetchesSites, ItFetchesServers, DiesIfEnvVariableIsnotInstalled;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:sites {--dump}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show sites on current server';

    /**
     * Sites.
     *
     * @var string
     */
    protected $sites;

    /**
     * Site names.
     *
     * @var array
     */
    protected $site_names;

    /**
     * Server names.
     *
     * @var Client
     */
    protected $http;

    /**
     * SaveEnvVariable constructor.
     *
     */
    public function __construct(Client $http)
    {
        parent::__construct();
        $this->http = $http;
    }

    /**
     * Handle command.
     */
    public function handle()
    {
        $this->abortCommandExecution();

        $this->sites = $this->fetchSites($server = fp_env('ACACHA_FORGE_SERVER'));
        $server_name = $this->serverName($server);
        $this->info("Sites on server $server_name ($server)");

        if ($this->option('dump')) {
            dump($this->sites);
        }

        if (empty($this->sites)) {
            $this->error('No Sites found.');
            die();
        }

        $headers = ['Id', 'Name','Type','Directory','Status','Repository','RepositoryStatus','QuickDeploy'];

        $rows = [];
        foreach ($this->sites as $site) {
            $rows[] = [
                $site->id,
                $site->name,
                $site->projectType,
                $site->directory,
                $site->status,
                $site->repository,
                $site->repositoryStatus,
                $site->quickDeploy
            ];
        }

        $this->table($headers, $rows);
    }

    /**
     * Server name.
     *
     * @param $server
     * @return mixed
     */
    protected function serverName($server)
    {
        return $this->getForgeName($this->fetchServers(), $server);
    }

    /**
     * Abort command execution.
     */
    protected function abortCommandExecution()
    {
        $this->dieIfEnvVarIsNotInstalled('ACACHA_FORGE_SERVER');
    }
}
