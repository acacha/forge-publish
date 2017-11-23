<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksForRootPermission;
use Acacha\ForgePublish\Commands\Traits\DNSisAlreadyConfigured;
use Acacha\ForgePublish\Commands\Traits\ItFetchesServers;
use Acacha\ForgePublish\Commands\Traits\ItFetchesSites;
use Acacha\ForgePublish\Commands\Traits\PossibleEmails;
use Acacha\ForgePublish\ForgePublishRCFile;
use Acacha\ForgePublish\Parser\ForgePublishRCParser;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use josegonzalez\Dotenv\Loader;

/**
 * Class ESBORRARPublishInit.
 *
 * @package Acacha\ForgePublish\Commands
 */
class ESBORRARPublishInit extends Command
{
    use ItFetchesSites,ItFetchesServers, PossibleEmails, ChecksForRootPermission, DNSisAlreadyConfigured;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Config publish command';

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
        $this->abortCommandExecution();
        $this->info('Hello! Together we are going to config Acacha Laravel Forge publish ...');
        $this->info('');
        $this->info('Let me check the requirements...');

        if (! ForgePublishRCFile::exists()) $this->executePublishRc();

        $this->info('');
        $this->info('Please visit and login on http:://forge.acacha.com.');
        $this->info('');
        $this->error('Please use Github Social Login for login!!!');

        while (! $this->confirm('User created at http:://forge.acacha.com?')) {}

        if ( ! $email = $this->env('ACACHA_FORGE_EMAIL') ) {
            $emails = $this->getPossibleEmails();
            $email = $this->anticipate('Ok! User email?', $emails);
        } else {
            $this->info("Ok! I see you already have a Forge user email configured so let's go on!...");
        }

        if ( ! $token = $this->env('ACACHA_FORGE_ACCESS_TOKEN')) {
            $this->info('I need permissions to operate in Acacha Forge in your name...');
            $this->info('So we need to obtain a valid token. Two options here:');
            $this->info('1) Login: You provide your user credentials and I obtain the token from Laravel Forge');
            $this->info('2) Personal Access Token: You provide a Personal Access token');

            $option = $this->choice('Which one you prefer?', ['Login', 'Personal Access Token'], 0);

            if ($option == 'Login') {
                $this->call('publish:login', [
                    'email' => $email
                ]);
            }
            else {
                $this->call('publish:token');
            }
        } else {
            $this->info("Ok! I see you already have a token for accessing Acacha Laravel Forge so let's go on!...");
        }

        $servers = $this->fetchServers();

        if ( ! $forge_id_server = $this->env('ACACHA_FORGE_SERVER')) {

            while (!$this->confirm('Server permissions requested at http:://forge.acacha.com?')) {
            }

            $server_names = collect($servers)->pluck('name')->toArray();
            if (empty($server_names)) {
                $this->error('No valid servers assigned to user!');
                die();
            }
            $server_name = $this->choice('Ok! Server name?', $server_names, 0);

            $forge_id_server = $this->getForgeIdServer($servers, $server_name);
        } else {
            $server_name = $this->getForgeName($servers, $forge_id_server);
            $this->info("Ok! I see you already have a Forge server configured so let's go on!...");
        }

        if ( ! $domain = $this->env('ACACHA_FORGE_DOMAIN') ) {
            $domain = $this->ask('Domain in production?',$this->defaultDomain());
        } else {
            $this->info("Ok! I see you already have a domain configured so let's go on!...");
        }
        $server_id = env('ACACHA_FORGE_SERVER',null) ? env('ACACHA_FORGE_SERVER') : $forge_id_server;
        $sites = $this->fetchSites($server_id);
        $site_names = collect($sites)->pluck('name')->toArray();
        if ( ! $site_id = $this->env('ACACHA_FORGE_SITE') ) {
            $site_name = $this->anticipate('Which site?', $site_names, $domain);
            $site_id = $this->getSiteId($sites, $site_name);
        } else {
            $this->info("Ok! I see you already have a site configured so let's go on!...");
        }

        $ip_address = $this->serverIpAddress($servers,$server_id);

        if ( env('ACACHA_FORGE_GITHUB_REPO', null) != null) {
            $github_repo = env('ACACHA_FORGE_GITHUB_REPO');
            $this->info("Ok! I see you already have a github repo configured so let's go on!...");
        }   else {
            $this->call('publish:git');
            $github_repo = $this->env('ACACHA_FORGE_GITHUB_REPO');
        }

        $this->info('');
        $this->info('Ok! let me resume: ');

        $headers = ['Task/Config name', 'Done/result?'];
        $tasks = [
          [ 'User created at http:://forge.acacha.com?', 'Yes'],
          [ 'Email', $email],
          [ 'Acacha Forge Token obtained ', 'Yes'],
          [ 'Server permissions requested at http:://forge.acacha.com?', 'Yes'],
          [ 'Server name', $server_name],
          [ 'Server Forge id', $forge_id_server],
          [ 'Domain', $domain],
          [ 'Server site id', $site_id ? $site_id : 'Site not created yet' ],
          [ 'Server IP address', $ip_address ],
          [ 'Github repo', $github_repo ? $github_repo : 'Repo not created yet' ]
        ];

        $this->table($headers, $tasks);

        $this->info('');
        if ( env('ACACHA_FORGE_EMAIL', null) == null) {
            $this->call('publish:email', [
                'email' => $email
            ]);
        }
        if ( env('ACACHA_FORGE_SERVER', null) == null) {
            $this->call('publish:server', [
                'server' => $forge_id_server
            ]);
        }
        if ( env('ACACHA_FORGE_DOMAIN', null) == null) {

            $this->call('publish:domain', [
                'domain' => $domain
            ]);
        }

        if ( env('ACACHA_FORGE_SITE', null) == null && $site_id) {
            $this->call('publish:site', [
                'site' => $site_id
            ]);
        }

        if ( env('ACACHA_FORGE_IP_ADDRESS', null) == null) {
            $this->call('publish:ip', [
                'ip_address' => $ip_address
            ]);
        }

        $this->info('');
        $this->info('Perfect! All info is saved to your environment. Enjoy Acacha forge publish!');
        $this->info('');
        $this->error('Remember to rerun your server to apply changes in .env file!!!');
        $this->info('');

        if ( ! in_array($domain, $site_names) )  {
            $this->info("It seems you don't have a Laravel Forge site created with domain: $domain");

            if ($this->confirm("Do you want to create site ($domain)?")) {
                $this->call('publish:create_site', [
                        'forge_server' => $forge_id_server,
                        'domain' => $domain,
                        'project_type' => config('forge-publish.project_type'),
                        'site_directory' => config('forge-publish.site_directory'),
                    ]);

                $server_id = env('ACACHA_FORGE_SERVER',null) ? env('ACACHA_FORGE_SERVER') : $forge_id_server;

                $site_id = $this->getSiteId($sites, $domain);

                if ( env('ACACHA_FORGE_SITE', null) == null) {
                    $this->call('publish:site', [
                        'site' => $site_id
                    ]);
                }
            }
        }

        $this->call('publish:install_repo',[
            'repository' => $github_repo,
            '--server' => $forge_id_server,
            '--site' => $site_id,
        ]);

        if (! $this->dnsAlreadyConfigured ) {
            $this->call('publish:dns',[
                'ip' => $ip_address,
                'domain' => $domain,
                '--type' => 'hosts'
            ]);
        }

        $this->call('publish:ssh', [
            'email' => $email,
            'server_name' => $server_name .'_' . $server_id,
            'ip' => $ip_address,
        ]);

        if ($this->confirm('Do you want to install your project to production?')) {
            $this->call('publish:install', [
                '--server' => $server_name .'_' . $forge_id_server,
                '--domain' => $domain,
            ]);
        }

        if ($this->confirm('Do you want to enable Laravel Forge autodeploy?')) {
            $this->call('publish:autodeploy', [
                '--server' => $forge_id_server,
                '--site' => $site_id,
            ]);
        }

        $this->info('### SSL. Lets Encrypt will only work on sites with a valid domain (no /etc/hosts/trick) ###');
        $this->info("### Skip the next step if you don't need SSL or not have a valid domain name");

        if ($this->confirm('Do you want to enable SSL on site using Lets Encrypt?')) {
            $this->call('publish:ssl', [
                '--server' => $forge_id_server,
                '--domain' => $domain,
                '--site' => $site_id,
            ]);
        }

        if ($this->confirm('Do you want open your app in your default browser?')) {
            $this->call('publish:open', [
                  '--domain' => $domain
            ]);
        }

        $this->info("I have finished! Congratulations and enjoy!");
    }

    /**
     * Abort command execution.
     */
    protected function abortCommandExecution()
    {
        if ($this->dnsIsAlreadyConfigured()) return ;

        $this->checkForRootPermission();
    }

    /**
     * Default domain.
     *
     * @return string
     */
    protected function defaultDomain()
    {
        if ($suffix = $this->parser->getDomainSuffix()) return strtolower(camel_case(basename(getcwd()))) . '.' . $suffix;
        return '';
    }

    /**
     * Execute publish:rc command
     */
    protected function executePublishRc()
    {
        $this->call('publish:rc');
    }

    /**
     * Get forge site id from site name.
     *
     * @param $sites
     * @param $site_name
     * @return mixed
     */
    protected function getSiteId($sites, $site_name)
    {
        $site_found = collect($sites)->filter(function ($site) use ($site_name) {
            return $site->name === $site_name;
        })->first();

        if ( $site_found ) return $site_found->id;
        return null;
    }
}
