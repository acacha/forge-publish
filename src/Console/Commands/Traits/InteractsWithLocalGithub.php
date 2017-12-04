<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait InteractsWithLocalGithub.
 *
 * @package Acacha\ForgePublish\Commands\Traits
 */
trait InteractsWithLocalGithub
{
    /**
     * Get github repo from github.
     *
     * @return string
     */
    protected function getRepoFromGithubConfig()
    {
        $remote = `git remote get-url origin 2> /dev/null`;
        if (! starts_with($remote, ['git@github.com:','https://github.com/'])) {
            return '';
        }

        if (starts_with($remote, 'git@github.com:')) {
            // git@github.com:acacha/forge-publish.git
            return explode('.', explode(":", $remote)[1])[0];
        }
        if (starts_with($remote, 'https://github.com/')) {
//            https://github.com/acacha/llum.git
            return explode('.', str_replace('https://github.com/', '', $remote))[0];
        }
    }
}