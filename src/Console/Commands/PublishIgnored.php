<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksSSHConnection;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Class PublishIgnored.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishIgnored extends Command
{
    use ChecksSSHConnection;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:ignored';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish ignored files into production';

    /**
     * Server name
     *
     * @var String
     */
    protected $server;

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

        if (empty($ignored_files = $this->ignoredFiles())) {
            $this->error('Sorry no ignored files found in the current folder. Skipping');
            return;
        }

        $files_to_publish = $this->choice('Which ignored files do you want to publish (you could select multiple values separated by coma)?', $ignored_files, null, null, true);

        foreach ((array) $files_to_publish as $file) {
            $this->call('publish:scp', [
                'file' => $file
            ]);
        }
    }

    /**
     * Obtain ignored files
     */
    protected function ignoredFiles()
    {
        $lines = file($this->path());

        $ignored_files = [];
        foreach ($lines as $line) {
            $file = preg_replace("/\r|\n/", "", $line);
            if (!File::exists(base_path($file))) {
                continue;
            }
            $ignored_files[] = $file;
        }
        return $ignored_files;
    }

    /**
     * Git ignored files path.
     *
     * @return string
     */
    protected function path()
    {
        return base_path('.gitignore');
    }

    /**
     * Abort command execution?
     */
    protected function abortCommandExecution()
    {
        if (! File::exists($path = $this->path())) {
            $this->error("$path file not exists");
            die();
        }

        $this->abortIfNoSSHConnection();
    }
}
