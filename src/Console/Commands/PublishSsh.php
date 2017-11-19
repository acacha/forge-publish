<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\PossibleEmails;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;


/**
 * Class PublishSsh.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishSsh extends Command
{
    const SSH_ID_RSA = '/.ssh/id_rsa';
    const USR_BIN_SSH = '/usr/bin/ssh';

    use PossibleEmails;

    /**
     * Server name
     *
     * @var String
     */
    protected $server_name;

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
    protected $description = 'Add ssh configuration';

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
        $this->info("Configuring SSH...");

        if(! File::exists(self::USR_BIN_SSH)) {
            $this->info('No SSH client found on your system (' . self::USR_BIN_SSH .')!');
            $this->installSshClient();
        } else {
            $this->info('SSH client found in your system (' . self::USR_BIN_SSH .')...');
        }

        if ( File::exists($_SERVER['HOME'] . self::SSH_ID_RSA) ) {
            $this->info('SSH keys found on your system (~' . self::SSH_ID_RSA .')...');
        } else {
            $this->info('No SSH keys found on your system (~' . self::SSH_ID_RSA .')!');
            $this->createSSHKeys();
        }

        $this->appendSSHConfig();
        $this->testConnection();
    }

    /**
     * Append ssh config.
     */
    protected function appendSSHConfig()
    {
        $ssh_config_file = $_SERVER['HOME'] . '/.ssh/config';
        $this->info("Adding server config to SSH config file $ssh_config_file");
        if (! File::exists($ssh_config_file)) touch($ssh_config_file);
        $this->server_name = $this->argument('server_name') ? camel_case($this->argument('server_name')) : camel_case($this->ask('Server Name?'));
        $ip_address = $this->argument('ip') ? $this->argument('ip') : $this->ask('IP Address?');
        $this->validateIpAddress($ip_address);
        $config_string = "\nHost $this->server_name\n  Hostname $ip_address \n  User forge\n  IdentityFile /home/sergi/.ssh/id_rsa\n  Port 22\n";
        File::append($ssh_config_file,$config_string);

        $this->info('The following config has been added:' . $config_string);
    }

    /**
     * Validate ip address.
     *
     * @param $ip_address
     */
    protected function validateIpAddress($ip_address)
    {
        if ( !filter_var($ip_address, FILTER_VALIDATE_IP)) {
            $this->error("$ip_address is not a valid ip address! Exiting!");
            die();
        }
    }

    /**
     * Test connection
     */
    protected function testConnection()
    {
        $this->info('Testing connection...');
        $ret = exec('timeout 10 ssh -q ' . $this->server_name . ' exit; echo $?');

        if ($ret == 0 ) $this->info('Connection tested ok!');
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
