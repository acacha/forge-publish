<?php

namespace Acacha\ForgePublish\Commands;

use Illuminate\Console\Command;

/**
 * Class Publish.
 *
 * @package Acacha\ForgePublish\Commands
 */
class Publish extends Command
{
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
    protected $description = 'Publish your project to Laravel Forge';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {

        $this->call('publish:install_repo');

//        $this->call('publish:create_site');

//        $this->call('publish:create_site', [
//            'forge_server' => $forge_id_server,
//            'domain' => $domain,
//            'project_type' => config('forge-publish.project_type'),
//            'site_directory' => config('forge-publish.site_directory'),
//        ]);

//        if ( ! in_array($domain, $site_names) )  {
//            $this->info("It seems you don't have a Laravel Forge site created with domain: $domain");
//
//            if ($this->confirm("Do you want to create site ($domain)?")) {
//                $this->call('publish:create_site', [
//                    'forge_server' => $forge_id_server,
//                    'domain' => $domain,
//                    'project_type' => config('forge-publish.project_type'),
//                    'site_directory' => config('forge-publish.site_directory'),
//                ]);
//
//                $server_id = env('ACACHA_FORGE_SERVER',null) ? env('ACACHA_FORGE_SERVER') : $forge_id_server;
//
//                $site_id = $this->getSiteId($sites, $domain);
//
//                if ( env('ACACHA_FORGE_SITE', null) == null) {
//                    $this->call('publish:site', [
//                        'site' => $site_id
//                    ]);
//                }
//            }
//        }
//
//        $this->call('publish:install_repo',[
//            'repository' => $github_repo,
//            '--server' => $forge_id_server,
//            '--site' => $site_id,
//        ]);
//
//        if (! $this->dnsAlreadyConfigured ) {
//            $this->call('publish:dns',[
//                'ip' => $ip_address,
//                'domain' => $domain,
//                '--type' => 'hosts'
//            ]);
//        }
//
//        $this->call('publish:ssh', [
//            'email' => $email,
//            'server_name' => $server_name .'_' . $server_id,
//            'ip' => $ip_address,
//        ]);
//
//        if ($this->confirm('Do you want to install your project to production?')) {
//            $this->call('publish:install', [
//                '--server' => $server_name .'_' . $forge_id_server,
//                '--domain' => $domain,
//            ]);
//        }
//
//        if ($this->confirm('Do you want to enable Laravel Forge autodeploy?')) {
//            $this->call('publish:autodeploy', [
//                '--server' => $forge_id_server,
//                '--site' => $site_id,
//            ]);
//        }
//
//        $this->info('### SSL. Lets Encrypt will only work on sites with a valid domain (no /etc/hosts/trick) ###');
//        $this->info("### Skip the next step if you don't need SSL or not have a valid domain name");
//
//        if ($this->confirm('Do you want to enable SSL on site using Lets Encrypt?')) {
//            $this->call('publish:ssl', [
//                '--server' => $forge_id_server,
//                '--domain' => $domain,
//                '--site' => $site_id,
//            ]);
//        }
//
//        if ($this->confirm('Do you want open your app in your default browser?')) {
//            $this->call('publish:open', [
//                '--domain' => $domain
//            ]);
//        }
//
//        $this->info("I have finished! Congratulations and enjoy!");
    }

}
