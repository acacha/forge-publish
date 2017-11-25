<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksEnv;
use Acacha\ForgePublish\Commands\Traits\ChecksServer;
use Acacha\ForgePublish\Commands\Traits\ChecksSSHConnection;
use Acacha\ForgePublish\Commands\Traits\ItFetchesServers;
use Acacha\ForgePublish\Commands\Traits\PossibleEmails;
use Acacha\ForgePublish\Commands\Traits\DiesIfEnvVariableIsnotInstalled;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Class PublishSSH.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishSSH extends Command
{
    use PossibleEmails, ChecksSSHConnection, ChecksEnv, DiesIfEnvVariableIsnotInstalled;

    /**
     * SSH_ID_RSA_PRIV
     */
    const SSH_ID_RSA_PRIV = '/.ssh/id_rsa';

    /**
     * SSH_ID_RSA_PUB
     */
    const SSH_ID_RSA_PUB = '/.ssh/id_rsa.pub';

    /**
     * USR_BIN_SSH
     */
    const USR_BIN_SSH = '/usr/bin/ssh';

    /**
     * Servers.
     *
     * @var array
     */
    protected $servers;

    /**
     * Server.
     *
     * @var array
     */
    protected $server;

    /**
     * Server name.
     *
     * @var array
     */
    protected $server_name;

    /**
     * Server names.
     *
     * @var array
     */
    protected $server_names;

    /**
     *API endpint URL
     *
     * @var String
     */
    protected $url;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:ssh {email?} {server_name?} {ip?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add ssh configuration and publish SSH keys to Laravel Forge server';

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
        $this->info("Configuring SSH...");

        $this->checkForSSHClientOrInstall();

        $this->checkForSSHKeysOrCreate();

        $this->obtainServersInfo();

        $this->appendSSHConfig();

        $this->abortCommandExecution();
        $this->installSSHKeyOnServer();

        $this->testSSHConnection();
    }

    /**
     * Check for SSH client or install it if missing.
     */
    protected function checkForSSHClientOrInstall()
    {
        if(! File::exists(self::USR_BIN_SSH)) {
            $this->info('No SSH client found on your system (' . self::USR_BIN_SSH .')!');
            $this->installSshClient();
        } else {
            $this->info('SSH client found in your system (' . self::USR_BIN_SSH .')...');
        }
    }

    /**
     * Check for SSH client or install it if missing.
     */
    protected function checkForSSHKeysOrCreate()
    {
        if ( File::exists($_SERVER['HOME'] . self::SSH_ID_RSA_PRIV) ) {
            $this->info('SSH keys found on your system (~' . self::SSH_ID_RSA_PRIV .')...');
        } else {
            $this->info('No SSH keys found on your system (~' . self::SSH_ID_RSA_PRIV .')!');
            $this->createSSHKeys();
        }
    }

    protected function obtainServersInfo()
    {

        $this->obtainServers();
        $this->obtainServer();
    }

    /**
     * Obtain servers.
     */
    protected function obtainServers()
    {
        $this->servers = $this->fetchServers();
        $this->server_names = collect($this->servers)->pluck('name')->toArray();
        if (empty($this->server_names)) {
            $this->error('No valid servers assigned to user. Skipping SSH key installation on server...');
            die();
        }
    }

    /**
     * Obtain server.
     */
    protected function obtainServer()
    {
        if (! $this->server) {
            if ($this->argument('server_name')) {
                $this->server = $this->getForgeIdServer($this->servers, $this->argument('server_name'));
                if (!$this->server) {
                    $this->error('No server name found on servers assigned to user!');
                    die();
                }
                $this->server_name = $this->argument('server_name');

            } else {
                if (fp_env('ACACHA_FORGE_SERVER')) {
                    $this->server = fp_env('ACACHA_FORGE_SERVER');
                    if (fp_env('ACACHA_FORGE_SERVER_NAME')) {
                        $this->server_name = fp_env('ACACHA_FORGE_SERVER_NAME');
                    } else {
                        $this->server_name = $this->getForgeName($this->servers, $this->server);
                    }
                } else {
                    $this->server_name = $this->choice('Forge server?', $this->server_names, 0);
                    $this->server = $this->getForgeIdServer($this->servers, $this->server_name);
                }
            }
        }

        $this->checkServer($this->server);
        $this->checkServerName($this->server_name);
    }

    /**
     * Get ENDPOINT API URL.
     *
     * @return string
     */
    protected function endPointAPIURL()
    {
        $uri = str_replace('{forgeserver}', $this->server , config('forge-publish.post_ssh_keys_uri'));
        return config('forge-publish.url') . $uri;
    }

    /**
     * Install ssh key on server.
     */
    protected function installSSHKeyOnServer()
    {
        $this->info('Adding SSH key to Laravel Forge server');
        // We cannot use ssh-copy-id -i ~/.ssh/id_rsa.pub forge@146.185.164.54 because SSH acces via user/password is not enabled on Laravel Forge
        // We need to use the Laravel Forge API to add a key

        $this->url = $this->endPointAPIURL();

        $response = $this->postKeyToLaravelForgeServer($keyName = $this->getUniqueKeyNameFromEmail());

        $result = json_decode($contents = $response->getBody()->getContents());

        if (! isset($result->status)) {
            $this->error("An error has been succeded: $contents");
            die();
        }
        if ($result->status == 'installed') $this->info("The SSH Key ($keyName) has been correctly installed in Laravel Forge Server " . $this->server_name . ' (' . $this->server . ')');
    }

    /**
     * Post key to Laravel Forge Server.
     */
    protected function postKeyToLaravelForgeServer($keyName=null)
    {
        $keyName ? $keyName : $this->getUniqueKeyNameFromEmail();
        return $this->http->post($this->url,
            [
                'form_params' => [
                    'name' => $keyName,
                    'key' => file_get_contents($_SERVER['HOME'] . self::SSH_ID_RSA_PUB)
                ],
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . fp_env('ACACHA_FORGE_ACCESS_TOKEN')
                ]
            ]
        );
    }

    /**
     * Abort command execution?
     */
    protected function abortCommandExecution()
    {
        $this->dieIfEnvVarIsNotInstalled('ACACHA_FORGE_ACCESS_TOKEN');
    }

    /**
     * Get unique key name from email.
     */
    protected function getUniqueKeyNameFromEmail()
    {
        return str_slug($this->argument('email') ? $this->argument('email') : $this->getEmail()) . '_' . str_random(10);
    }

    /**
     * Append ssh config.
     */
    protected function appendSSHConfig()
    {
        $ssh_config_file = $this->sshConfigFile();

        $hostname = $this->hostNameForConfigFile();

        $host_string = "Host " . $hostname;

        if( strpos(file_get_contents($ssh_config_file), $host_string) !== false) {
            $this->info("SSH config for host: $host_string already exists");
            return;
        }

        $this->info("Adding server config to SSH config file $ssh_config_file");
        if (! File::exists($ssh_config_file)) touch($ssh_config_file);
        $ip_address = $this->ip_address();
        $config_string = "\n$host_string\n  Hostname $ip_address \n  User forge\n  IdentityFile /home/sergi/.ssh/id_rsa\n  Port 22\n  StrictHostKeyChecking no\n";
        File::append($ssh_config_file,$config_string);

        $this->info('The following config has been added:' . $config_string);
    }

    /**
     * IP address.
     *
     * @return array|string
     */
    protected function ip_address()
    {
        if (fp_env('ACACHA_FORGE_IP_ADDRESS')) {
            $ip_address = fp_env('ACACHA_FORGE_IP_ADDRESS');
        } else {
            $ip_address = $this->argument('ip') ? $this->argument('ip') : $this->ask('IP Address?');
        }

        $this->validateIpAddress($ip_address);
        return $ip_address;
    }

    /**
     * Validate ip address.
     *
     * @param $ip_address
     */
    protected function validateIpAddress($ip_address)
    {
        if ( ! filter_var($ip_address, FILTER_VALIDATE_IP) ) {
            $this->error("$ip_address is not a valid ip address! Exiting!");
            die();
        }
        if ( ! $this->checkIp($ip_address, $this->servers)) {
            $this->error("$ip_address doesn't match any of your IP addreses Forge Servers");
            die();
        }
    }

    /**
     * Test SSH connection.
     */
    protected function testSSHConnection()
    {
        $this->info('Testing connection...');
        if ( $this->checkSSHConnection($this->hostNameForConfigFile()) ) $this->info('Connection tested ok!');
        else $this->error('Error connnecting to server!');
    }

    /**
     * Create SSH Keys
     */
    protected function createSSHKeys()
    {
        $email = $this->argument('email') ? $this->argument('email') : $this->getEmail();
        $this->info("Running ssh-keygen -t rsa -b 4096 -C '$email'");
        passthru('ssh-keygen -t rsa -b 4096 -C "' . $email . '"');
    }

    /**
     * Get email.
     *
     * @return string
     */
    protected function getEmail()
    {
        return array_key_exists(0, $emails = $this->getPossibleEmails()) ? $emails[0] : $this->ask('Email:');
    }

    /**
     * Install ssh client.
     */
    protected function installSshClient()
    {
        $this->info('Running sudo apt-get install ssh');
        passthru('sudo apt-get install openssh-client');
    }

}
