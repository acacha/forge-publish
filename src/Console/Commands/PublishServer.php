<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ItFetchesServers;
use GuzzleHttp\Client;

/**
 * Class PublishServer.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishServer extends SaveEnvVariable
{
    use ItFetchesServers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:server {server?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save acacha forge server';

    /**
     * Servers.
     *
     * @var array
     */
    protected $servers;

    /**
     * Server.
     *
     * @var string
     */
    protected $server;

    /**
     * Server names.
     *
     * @var array
     */
    protected $server_names;

    /**
     * Server names.
     *
     * @var array
     */
    protected $server_ids;

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
     * Env var to set.
     *
     * @return mixed
     */
    protected function envVar()
    {
        return 'ACACHA_FORGE_SERVER';
    }

    /**
     * Argument key.
     *
     * @return mixed
     */
    protected function argKey()
    {
        return 'server';
    }

    /**
     * Question text.
     *
     * @return mixed
     */
    protected function questionText()
    {
        return 'Acacha forge server (forge id)?';
    }

    /**
     * Before hook.
     */
    protected function before()
    {
        while (! $this->confirm('Do you have a validated server assigned at http:://forge.acacha.com?')) {
        }
        $this->servers = $this->fetchServers();
        if (empty($this->servers)) {
            $this->error('No valid servers assigned to user!');
            die();
        }
        $this->server_names = collect($this->servers)->pluck('name')->toArray();
        $this->server_ids = collect($this->servers)->pluck('forge_id')->toArray();
    }

    /**
     * After hook.
     */
    protected function after()
    {
        $ip_address = $this->serverIpAddress($this->servers, $this->server);
        $server_name = $this->getForgeName($this->servers, $this->server);
        $this->call("publish:ip", [
            'ip' => $ip_address
        ]);
        $this->call("publish:server_name", [
            'server_name' => $server_name
        ]);
    }

    /**
     * Default proposed value when asking.
     *
     */
    protected function default()
    {
        return array_search(fp_env('ACACHA_FORGE_SERVER'), $this->server_ids);
    }

    /**
     * Value.
     */
    protected function value()
    {
        $server_name = $this->choice($this->questionText(), $this->server_names, $this->default());
        return $this->server = $this->getForgeIdServer($this->servers, $server_name);
    }
}
