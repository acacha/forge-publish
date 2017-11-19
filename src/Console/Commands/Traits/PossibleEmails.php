<?php

namespace Acacha\ForgePublish\Commands\Traits;
use Illuminate\Support\Facades\File;

/**
 * Trait PossibleEmails
 *
 * @package Acacha\ForgePublish\Commands\Traits
 */
trait PossibleEmails
{

    /**
     * Get possible emails
     *
     * @return array
     */
    protected function getPossibleEmails()
    {
        $this->checkGit();
        $github_email = null;
        $github_email = str_replace(array("\r", "\n"), '', shell_exec('git config user.email'));

        if(filter_var($github_email, FILTER_VALIDATE_EMAIL)) return [ $github_email ];
        else return [];
    }

    /**
     * Check git.
     */
    protected function checkGit()
    {
        if (File::exists('/usr/bin/git')) return;
        $this->info('Git not found in your system');
        $this->info('Installing git with sudo apt-get install git');
        passthru('sudo apt-get install git');
    }
}