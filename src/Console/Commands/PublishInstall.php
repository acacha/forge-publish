<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\ChecksSSHConnection;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use josegonzalez\Dotenv\Loader;

/**
 * Class PublishInstall.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishInstall extends Command
{
    use ChecksEnv,ChecksSSHConnection;

    /**
     * Server name
     *
     * @var String
     */
    protected $server;

    /**
     * Domain
     *
     * @var String
     */
    protected $domain;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:install {--server=} {--domain=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install project into production Laravel Forge server';

    /**
     * Is mysq database already configured.
     *
     * @var boolean
     */
    protected $databaseAlreadyConfigured = false;

    /**
     * Guzzle http client.
     *
     * @var Client
     */
    protected $http;

    /**
     * Create a new command instance.
     *
     */
    public function __construct(Client $http)
    {
        parent::__construct();
        $this->http = $http;
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->abortCommandExecution();
        $this->info("I'm going to install this project to production...");

        if ($this->confirm('Do you have ignored local files you want to add to production?')) {
            $this->call('publish:ignored');
        }

        if ($this->confirm('Do you have ignored local Github projects that do you want to add to production?')) {
            $this->call('publish:git_dependencies');
        }

        $this->call('publish:composer', [
            'composer_command' => 'install'
        ]);

        $this->call('publish:npm', [
            'npm_command' => 'install',
        ]);

        $this->call('publish:key_generate');

        check_env_production:

        if (! File::exists($productionEnv = base_path('.env.production'))) {
            $this->error("File $productionEnv not found!");
            $this->line("Be careful! Without $productionEnv file the default Laravel Forge .env will be applied.");
            $this->line("For example default database forge will be used. This could affect other apps in production using the same database");
            if ($this->confirm('Do you want to create the File? please choose yes once you have been created the file!')) {
                goto check_env_production;
            }
        } else {
            if ( ! File::exists($gitIgnore = base_path('.gitignore')) ) {
                $this->error("Be careful! File $gitIgnore doesn't exists and you have a $productionEnv file. Be sure you are not publishing sensible data to your repo!");
            } else {
                if( strpos(file_get_contents($gitIgnore),".env.production") === false) {
                    $this->error("Be careful! File $gitIgnore doesn't have $productionEnv file. Be sure you are not publishing sensible data to your repo!");
                }
            }

            if ($this->confirm('Do you wish to install .env.production file and overwrite .env in production?')) {
                $this->call('publish:env-production');
            }

            $mysql_data = $this->obtainMySQLDatabaseInfoFromEnv(base_path('.env.production'));

            if ( ! $mysql_data) {
                $this->error('Not all MYSQL info (DB_CONNECTION=mysql,DB_DATABASE,DB_USER,DB_PASSWORD,) found in .env production');
            } else {
                $this->info("We have found a MYSQL database and user configuration in your .env.production file.");
                $this->line("Database type: MySQL");
                $this->line("Database name: " . $mysql_data['name']);
                $this->line("Database user: " . $mysql_data['user']);
                if ($this->confirm('Do you want to create this database and user in production?')) {
                    $this->call('publish:mysql', array_merge($mysql_data,['--wait']));
                }
                $this->databaseAlreadyConfigured = true;
            }
        }

        if ( ! $this->databaseAlreadyConfigured) {
            if ($this->confirm('Do you want to create a database in production?')) {
                $mysql_data['name'] = $this->ask('Database?');
                $mysql_data['user'] = $this->ask('User?');
                $mysql_data['password'] = $this->secret('Password?');
                $this->call('publish:mysql', $mysql_data);
            }
        }

        if ($this->confirm('Do you wish run migrations on production?')) {
            $this->call('publish:artisan',[
                'artisan_command' => 'migrate --force',
            ]);
        }

        if ($this->confirm('Do you want to seed database on production?')) {
            $this->call('publish:artisan',[
                'artisan_command' => 'db:seed --force',
            ]);
        }
    }

    /**
     * Obtain MySQL Database Info From env file.
     *
     * @param $file
     * @return array
     */
    protected function obtainMySQLDatabaseInfoFromEnv($file)
    {
        $env = (new Loader($file))->parse()->toArray();

        if ( ! array_key_exists('DB_CONNECTION',$env)  || $env['DB_CONNECTION'] != 'mysql' ) return null;

        if ( ! array_key_exists('DB_DATABASE',$env) ||
             ! array_key_exists('DB_USERNAME',$env) ||
             ! array_key_exists('DB_PASSWORD',$env)) return null;

        return [
          'name' => $env['DB_DATABASE'],
          'user' => $env['DB_USERNAME'],
          'password' => $env['DB_PASSWORD'],
        ];

    }

    /**
     * Abort command execution?
     */
    protected function abortCommandExecution()
    {
        $this->server = $this->checkEnv('server','ACACHA_FORGE_SERVER');
        $this->domain = $this->checkEnv('domain','ACACHA_FORGE_DOMAIN');

        $this->abortIfNoSSHConnection();
    }

}
