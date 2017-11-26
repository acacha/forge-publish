<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\ChecksSSHConnection;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Class PublishGitDependencies.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishGitDependencies extends Command
{
    use ChecksSSHConnection;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:git_dependencies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish ignored local git projects into production';

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

        if ( empty($ignored_repos = $this->ignoredRepos())) {
            $this->error('Sorry no ignored local github repos found in the current folder. Skipping');
            return;
        }

        $files_to_publish = $this->choice('Which Github projects in local do you want to publish (you could select multiple values separated by coma)?', $ignored_repos ,null,null,true);

        foreach ((array) $files_to_publish as $file) {
            $repository_url= $this->repositoryURL($file);
            $file = starts_with($file,'/') ? str_after($file, '/') : $file;
            $this->call('publish:git', [
                'git_command' => "clone $repository_url $file"
            ]);
        }
    }

    /**
     * Obtain ignored files
     */
    protected function ignoredRepos()
    {
        $lines = file($this->ignored_files_path());

        $ignored_files = [];
        foreach ($lines as $line) {
            $file = preg_replace( "/\r|\n/", "", $line );
            if (!File::exists(base_path($file)) || ! is_dir(base_path($file))) continue;
            if (! $this->folderContainsValidGithubRepo($file)) continue;
            $ignored_files[] = $file;
        }
        return $ignored_files;
    }

    /**
     * Repository URL.
     */
    protected function repositoryURL($file)
    {
        if ($this->isAGithubRepo($remote = $this->remoteGithub($file))) {
            return $remote;
        }

        $this->error("Sorry we could not find a valid Git URL for folder $file");
        die();
    }

    /**
     * Check if string is a valid Github repo URL.
     *
     * @return bool
     */
    protected function isAGithubRepo($remote)
    {
        if ( ! starts_with($remote,['git@github.com:','https://github.com/'])) {
            return false;
        }
        return true;
    }

    /**
     * Remote github.
     *
     * @param $file
     * @return mixed
     */
    protected function remoteGithub($file) {
        $path = base_path($file);
        if ( ! File::exists($path . '/.git')) return '';
        return preg_replace( "/\r|\n/", "", `cd $path;git remote get-url origin 2> /dev/null` );
    }

    /**
     * Folder contains a valid Github repo.
     *
     * @param $folder
     * @return bool
     */
    protected function folderContainsValidGithubRepo($folder)
    {
        $remote = $this->remoteGithub($folder);
        if ($this->isAGithubRepo($remote)) {
            return true;
        }
        return false;
    }

    /**
     * Git ignored files path.
     *
     * @return string
     */
    protected function ignored_files_path(){
        return base_path('.gitignore');
    }

    /**
     * Abort command execution?
     */
    protected function abortCommandExecution()
    {
        if(! File::exists($path = $this->ignored_files_path())) {
            $this->error("$path file not exists");
            die();
        }

        $this->abortIfNoSSHConnection();
    }

}
