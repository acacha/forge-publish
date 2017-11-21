<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ItFetchesServers;
use Acacha\ForgePublish\Commands\Traits\PossibleEmails;
use Acacha\ForgePublish\ForgePublishRCFile;
use Acacha\ForgePublish\Parser\ForgePublishRCParser;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use josegonzalez\Dotenv\Loader;

/**
 * Class PublishInit.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishInit extends Command
{
    use ItFetchesServers, PossibleEmails;

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
        $this->info('Hello! Together we are going to config Acacha Laravel Forge publish ...');
        $this->info('');
        $this->info('Let me check the requirements...');

        if (! ForgePublishRCFile::exists()) $this->executePublishRc();

        $this->info('');
        $this->info('Please visit and login on http:://forge.acacha.com.');
        $this->info('');
        $this->error('Please use Github Social Login for login!!!');

        while (! $this->confirm('User created at http:://forge.acacha.com?')) {}

        if ( env('ACACHA_FORGE_EMAIL', null) == null) {
            $emails = $this->getPossibleEmails();
            $email = $this->anticipate('Ok! User email?', $emails);
        } else {
            $email = env('ACACHA_FORGE_EMAIL');
            $this->info("Ok! I see you already have a Forge user email configured so let's go on!...");
        }

        $already_logged = false;

        if ( env('ACACHA_FORGE_ACCESS_TOKEN', null) == null) {
            $this->info('I need permissions to operate in Acacha Forge in your name...');
            $this->info('So we need to obtain a valid token. Two options here:');
            $this->info('1) Login: You provide your user credentials and I obtain the token from Laravel Forge');
            $this->info('2) Personal Access Token: You provide a Personal Access token');

            $option = $this->choice('Which on you prefer?', ['Login', 'Personal Access Token'], 0);

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
            $already_logged = true;
        }

        $servers = $already_logged ? $this->fetchServers() : $this->fetchServers($this->getTokenFromEnvFile());

        if ( env('ACACHA_FORGE_SERVER', null) == null) {

            while (!$this->confirm('Server permissions requested at http:://forge.acacha.com?')) {
            }

            $server_names = collect($servers)->pluck('name')->toArray();

            $server_name = $this->choice('Ok! Server name?', $server_names, 0);

            $forge_id_server = $this->getForgeIdServer($servers, $server_name);
        } else {
            $forge_id_server = env('ACACHA_FORGE_SERVER');
            $server_name = $this->getForgeName($servers, $forge_id_server);
            $this->info("Ok! I see you already have a Forge server configured so let's go on!...");
        }

        if ( env('ACACHA_FORGE_DOMAIN', null) == null) {
            $domain = $this->ask('Domain in production?',$this->defaultDomain());
        } else {
            $domain = env('ACACHA_FORGE_DOMAIN');
            $this->info("Ok! I see you already have a domain configured so let's go on!...");
        }
        $server_id = env('ACACHA_FORGE_SERVER',null) ? env('ACACHA_FORGE_SERVER') : $forge_id_server;
        $sites = $already_logged ? $this->fetchSites($server_id) : $this->fetchSites($server_id, $this->getTokenFromEnvFile());
        $site_id = null;
        $site_names = collect($sites)->pluck('name')->toArray();
        if ( env('ACACHA_FORGE_SITE', null) == null) {
            $site_name = $this->anticipate('Which site?', $site_names, $domain);
            $site_id = $this->getSiteId($sites, $site_name);
        } else {
            $site_id = env('ACACHA_FORGE_SITE');
            $this->info("Ok! I see you already have a site configured so let's go on!...");
        }

        $ip_address = $this->serverIpAddress($servers,$server_id);

        if ( env('ACACHA_FORGE_GITHUB_REPO', null) != null) {
            $github_repo = env('ACACHA_FORGE_GITHUB_REPO');
            $this->info("Ok! I see you already have a github repo configured so let's go on!...");
        }   else {
            $this->call('publish:git');
            $github_repo = env('ACACHA_FORGE_GITHUB_REPO');
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
                if ($already_logged) {
                    $this->call('publish:create_site', [
                        'forge_server' => $forge_id_server,
                        'domain' => $domain,
                        'project_type' => config('forge-publish.project_type'),
                        'site_directory' => config('forge-publish.site_directory')
                    ]);
                } else {
                    $this->call('publish:create_site', [
                        'forge_server' => $forge_id_server,
                        'domain' => $domain,
                        'project_type' => config('forge-publish.project_type'),
                        'site_directory' => config('forge-publish.site_directory'),
                        '--token' => $this->getTokenFromEnvFile()
                    ]);
                }

                $server_id = env('ACACHA_FORGE_SERVER',null) ? env('ACACHA_FORGE_SERVER') : $forge_id_server;
                if ($already_logged) {
                    $sites = $this->fetchSites($server_id);
                } else {
                    $sites = $this->fetchSites($server_id, $this->getTokenFromEnvFile());
                }

                $site_id = $this->getSiteId($sites, $domain);

                if ( env('ACACHA_FORGE_SITE', null) == null) {
                    $this->call('publish:site', [
                        'site' => $site_id
                    ]);
                }
            }

        }

        $this->call('publish:ssh', [
            'email' => $email,
            'server_name' => $server_id,
            'ip' => $ip_address
        ]);

        if ($this->confirm('Do you want to install your project to production?')) {
            $this->call('publish:install', [
                'email' => $email
            ]);
        }

        $this->info("DONE!!!!!!!!!!!");
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
     * Fetch sites
     */
    protected function fetchSites ($server_id, $token = null)
    {
        if (!$token) $token = env('ACACHA_FORGE_ACCESS_TOKEN');
        $url = config('forge-publish.url') . config('forge-publish.user_sites_uri') . '/' . $server_id;
        try {
            $response = $this->http->get($url,[
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . $token
                ]
            ]);
        } catch (\Exception $e) {
            $this->error('And error occurs connecting to the api url: ' . $url);
            $this->error('Status code: ' . $e->getResponse()->getStatusCode() . ' | Reason : ' . $e->getResponse()->getReasonPhrase() );
            return [];
        }
        return json_decode((string) $response->getBody());
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

    /**
     * Get token from env file
     *
     * @return mixed
     */
    protected function getTokenFromEnvFile()
    {
        //NOTE: We cannot use env() helper because the .env file has been changes in this request !!!
        return (new Loader(base_path('.env')))
            ->parse()
            ->toArray()['ACACHA_FORGE_ACCESS_TOKEN'];
    }

}
