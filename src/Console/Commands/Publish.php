<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\DNSisAlreadyConfigured;
use Illuminate\Console\Command;

/**
 * Class Publish.
 *
 * @package Acacha\ForgePublish\Commands
 */
class Publish extends Command
{
    use DNSisAlreadyConfigured;

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

        $this->call('publish:repository');

        $this->call('publish:dns');

        $this->call('publish:ssh');

        if ($this->confirm('Do you want to install your project to production?')) {
            $this->call('publish:install');
        }

        if ($this->confirm('Do you want to enable Laravel Forge autodeploy?')) {
            $this->call('publish:autodeploy');
        }

        $this->info('### SSL. Lets Encrypt will only work on sites with a valid domain (no /etc/hosts/trick) ###');
        $this->info("### Skip the next step if you don't need SSL or not have a valid domain name");

        if ($this->confirm('Do you want to enable SSL on site using Lets Encrypt?')) {
            $this->call('publish:ssl');
        }

        if ($this->confirm('Do you want open your app in your default browser?')) {
            $this->call('publish:open');
        }

        $this->info("I have finished publishing you project into Laravel Forge server! Congratulations and enjoy!");
    }

}
