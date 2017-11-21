<?php

namespace Acacha\ForgePublish\Commands;

use Illuminate\Console\Command;

/**
 * Class PublishKey.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishKey extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install key on production';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->abortCommandExecution();

        $this->info("I'm going to install cipher key to production using php artisan key:generate...");

//        cat .env | grep APP_KEY
//APP_KEY=base64:ugyvBumqIuTJ7U/o0kMnjsyF5aPUoAKQoKu3lTChia0=
//    PUSHER_APP_KEY=

//        php artisan key:generate --force

    }

    /**
     * Abort command execution.
     */
    protected function abortCommandExecution()
    {
//        $domain = env('ACACHA_FORGE_DOMAIN',null);
//        $ip = env('ACACHA_FORGE_IP_ADDRESS',null);
//
//        if ($domain != null && $ip != null ) {
//            $resolved_ip = gethostbyname ($domain);
//            if ( $resolved_ip != $domain && $resolved_ip == $ip ) {
//                $this->dnsAlreadyConfigured = true;
//                return;
//            }
//        }

    }


}
