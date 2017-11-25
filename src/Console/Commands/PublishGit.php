<?php

namespace Acacha\ForgePublish\Commands;

/**
 * Class PublishGit.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishGit extends SaveEnvVariable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:git {repo?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save acacha forge github repo';

    /**
     * Env var to set.
     *
     * @return mixed
     */
    protected function envVar()
    {
        return 'ACACHA_FORGE_GITHUB_REPO';
    }

    /**
     * Argument key.
     *
     * @return mixed
     */
    protected function argKey()
    {
        return 'repo';
    }

    /**
     * Question text.
     *
     * @return mixed
     */
    protected function questionText()
    {
        return 'Acacha forge Github Repo (vendor/name)?';
    }

    /**
     * Default proposed value when asking.
     *
     */
    protected function default() {
        $default = $this->getRepoFromGithubConfig();
        if ( ! $default) {
            $this->error('No Github Repository found!');
            if ($this->confirm('Do you want to run llum github:init command (requires llum installed!)?')) {
                passthru('llum github:init');
            }
        }
        return fp_env($this->envVar()) ? fp_env($this->envVar()) : $default;
    }

    /**
     * Get github repo from github.
     *
     * @return string
     */
    protected function getRepoFromGithubConfig()
    {
        $remote = `git remote get-url origin 2> /dev/null`;
        if ( ! starts_with($remote,'git@github.com:')) return '';
        return explode('.', explode(":", $remote)[1])[0];
    }
}
