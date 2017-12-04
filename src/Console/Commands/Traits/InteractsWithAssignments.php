<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait InteractsWithAssignments.
 *
 * @package Acacha\ForgePublish\Commands\Traits
 */
trait InteractsWithAssignments
{
    /**
     * Ask assignment.
     */
    protected function askAssignment()
    {
        $this->assignments = $this->fetchAssignments();
        $names = collect($this->assignments)->pluck('name')->toArray();
        $default = array_search($this->currentAssignment(),$names);
        $selected = $this->choice('Assignment?',$names,$default);
        return $this->findAssignmentByName($selected)->id;
    }

    /**
     * Current assignment
     */
    protected function currentAssignment() {
        if (fp_env('ACACHA_FORGE_ASSIGNMENT')) {
            $assignmentFound = collect($this->assignments)->filter(function ($assignment) {
                return $assignment->id == fp_env('ACACHA_FORGE_ASSIGNMENT');
            })->first();
            if ($assignmentFound) return $assignmentFound->name;
        }
        return null;
    }

    /**
     * Find assignment by name.
     *
     * @param $name
     * @return null
     */
    protected function findAssignmentByName($name)
    {
        $assignmentFound = collect($this->assignments)->filter(function ($assignment) use ($name) {
            return $assignment->name == $name;
        })->first();
        if ($assignmentFound) return $assignmentFound;
        return null;
    }

    /**
     * Ask forge site.
     *
     * @return string
     */
    protected function askForgeSite()
    {
        $default = $this->defaultForgeSite();
        return $this->ask('Forge site?',$default);
    }

    /**
     * Ask forge server.
     *
     * @return string
     */
    protected function askForgeServer()
    {
        $default = $this->defaultForgeServer();
        return $this->ask('Forge server?',$default);
    }

    /**
     * Default forge site
     */
    protected function defaultForgeSite()
    {
        return fp_env('ACACHA_FORGE_SITE') ? fp_env('ACACHA_FORGE_SITE') : null;
    }

    /**
     * Default forge server.
     */
    protected function defaultForgeServer()
    {
        return fp_env('ACACHA_FORGE_SERVER') ? fp_env('ACACHA_FORGE_SERVER') : null;
    }

    /**
     * Ask name.
     */
    protected function askName()
    {
        $default = $this->defaultName();
        return $this->ask('name?',$default);
    }

    /**
     * Ask repository Uri.
     */
    protected function askRepositoryUri() {
        $default = $this->defaultRepositoryUri();
        return $this->ask('Repository URI?', $default);
    }

    /**
     * Ask repository Uri.
     */
    protected function askRepositoryType() {
        $default = $this->defaultRepositoryType();
        return $this->ask('Repository type?', $default);
    }

    /**
     * Default repository type.
     *
     * @return string
     */
    protected function defaultRepositoryType()
    {
        return 'github';
    }

    /**
     * Default repository URI.
     *
     * @return null
     */
    protected function defaultRepositoryUri()
    {
        return fp_env('ACACHA_FORGE_GITHUB_REPO') ? fp_env('ACACHA_FORGE_GITHUB_REPO') : $this->getRepoFromGithubConfig();
    }

    /**
     * Default name.
     *
     * @return null|string
     */
    protected function defaultName()
    {
        return fp_env('ACACHA_FORGE_DOMAIN') ? fp_env('ACACHA_FORGE_DOMAIN') : strtolower(camel_case(basename(getcwd())));
    }
}