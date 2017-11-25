<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\ForgePublishRCFile;
use Illuminate\Console\Command;

/**
 * Class PublishInit.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishInit extends Command
{
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
    protected $description = 'Config acacha forge publish ';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->info('Hello! Together we are going to config Acacha Laravel Forge publish ...');

        if (! ForgePublishRCFile::exists()) $this->call('publish:rc');;

        $this->confirmAnUserIsCreatedInAcachaLaravelForge();

        $this->call('publish:url');

        $this->call('publish:email');

        $this->login();

        $this->call('publish:check_token');

        $this->call('publish:server');

        $this->call('publish:domain');

        $this->call('publish:project_type');

        $this->call('publish:site_directory');

        $this->call('publish:site');

        $this->call('publish:git');

        $this->finish();

    }

    /**
     * Confirm an user is created in Acacha Laravel Forge.
     */
    protected function confirmAnUserIsCreatedInAcachaLaravelForge()
    {
        $this->info('');
        $this->info('Please visit and login on http:://forge.acacha.com.');
        $this->info('');
        $this->error('Please use Github Social Login for login!!!');

        while (! $this->confirm('Do you have an user created at http:://forge.acacha.com?')) {}
    }

    /**
     * Login
     */
    protected function login()
    {
        if ( fp_env('ACACHA_FORGE_ACCESS_TOKEN')) {
            $this->info('You have a token already configured in your environment.');
            if (! $this->confirm('Do you want to relogin?')) return;
        }

        $this->info('I need permissions to operate in Acacha Forge in your name...');
        $this->info('So we need to obtain a valid token. Two options here:');
        $this->info('1) Login: You provide your user credentials and I obtain the token from Laravel Forge');
        $this->info('2) Personal Access Token: You provide a Personal Access token');

        $option = $this->choice('Which one you prefer?', ['Login', 'Personal Access Token'], 0);

        if ($option == 'Login') {
            $this->call('publish:login', [
                'email' => fp_env('ACACHA_FORGE_EMAIL')
            ]);
        }
        else {
            $this->call('publish:token');
        }
    }

    /**
     * Finish command.
     */
    protected function finish()
    {
        $this->info('');
        $this->info('Ok! let me resume: ');
        $this->call('publish:info');

        $this->info('');
        $this->info('Perfect! All info is saved to your environment (.env file).');
        $this->info('');
        $this->error('If needed, remember to rerun your server to apply changes in .env file!!!');
        $this->info('');

        $this->info("I have finished! Congratulations and enjoy Acha Laravel Forge!");

        $this->info("You are now ready to publish your awesome project to Laravel Forge using:");

        $this->info("**** php artisan publish ***");
    }
}
