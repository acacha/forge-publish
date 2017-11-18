<?php

namespace Acacha\ForgePublish\Commands;

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
    protected $description = 'Config publish command';

    /**
     * Create a new command instance.
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
        $this->info('Hello! We are going to config Acacha Laravel Forge publish together...');
        $this->info('Let me check you have been followed all the previous requirements...');

        while (! $this->confirm('User created at http:://forge.acacha.com?')) {}

        $email = $this->ask('Ok! User email?');

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
            $this->info("Ok I see you already have a token for accessing Acacha Laravel Forge so let's go on!...");
        }

        while (! $this->confirm('Server permissions requested at http:://forge.acacha.com?')) {}

        $server = $this->ask('Ok! Server name?');

        $domain = $this->ask('Domain in production?');

        $this->info('Ok let me resume: ');

        $headers = ['Task name', 'Done/result?'];

        $tasks = [
          [ 'User created at http:://forge.acacha.com?', 'Yes'],
          [ 'Email', $email],
          [ 'Token obtained', 'Yes'],
          [ 'Server permissions requested at http:://forge.acacha.com?', 'Yes'],
          [ 'Server', $server],
          [ 'domain', $domain],
        ];

        $this->table($headers, $tasks);

        // LOGIN -> Two options:

        // 1 Personal access token
        $this->info('Please go to https://forge.acacha.org/passport-tokens an create a Personal Access Token');
        $token = $this->ask('Personal Access Token?');

        // 2 Login. Execute publish:login command

    }
}
