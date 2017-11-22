<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\ChecksForRootPermission;
use Acacha\ForgePublish\Commands\Traits\DNSisAlreadyConfigured;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Class PublishDNS.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishDNS extends Command
{

    use ChecksForRootPermission, DNSisAlreadyConfigured, ChecksEnv;

    /**
     * Constant to /etc/hosts file
     */
    const ETC_HOSTS = '/etc/hosts';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:dns {ip?} {domain?} {type?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check DNS configuration';

    /**
     * The domain name.
     *
     * @var string
     */
    protected $domain;

    /**
     * The ip address.
     *
     * @var string
     */
    protected $ip;

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->info('Checking DNS configuration');
        $this->abortCommandExecution();
        $resolved_ip = gethostbyname ($this->domain);
        $this->info("domain: $this->domain | IP : $resolved_ip");
        if ( $resolved_ip != $this->domain && $resolved_ip == $this->ip ) {
            $this->info("DNS resolution is ok. ");
            return;
        }

        $this->info("DNS resolution is not configured ok. Let me help you configure it...");

        $type = $this->argument('type') ?
            $this->argument('type') :
            $this->choice('Which system do you want to use?',['hosts'],0);

        if ($type != 'hosts') {
            //TODO Support Other services OpenDNS/Hover.com? DNS service wiht API
            // https://laracasts.com/series/server-management-with-forge/episodes/8
            $this->error('Type not supported');
            die();
        }

        passthru('sudo true');

        $this->addEntryToEtcHostsFile($this->domain,$this->ip);
        $this->info('File ' . self::ETC_HOSTS . ' configured ok');
    }

    /**
     * Add entry to etc/hosts file.
     *
     * @param $domain
     * @param $ip
     */
    protected function addEntryToEtcHostsFile($domain, $ip)
    {
        $content = "\n# Forge server\n$ip $domain\n";
        File::append(self::ETC_HOSTS,$content);
    }

    /**
     * Abort command execution.
     */
    protected function abortCommandExecution()
    {
        $this->domain = $this->checkEnv('domain','ACACHA_FORGE_DOMAIN');
        $this->ip = $this->checkEnv('forge_server','ACACHA_FORGE_IP_ADDRESS');

        if ($this->dnsIsAlreadyConfigured()) return ;

        $this->checkForRootPermission();
    }
}
