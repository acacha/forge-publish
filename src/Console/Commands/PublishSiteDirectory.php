<?php

namespace Acacha\ForgePublish\Commands;

/**
 * Class PublishSiteDirectory.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishSiteDirectory extends SaveEnvVariable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:site_directory {site_directory?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save Acacha forge site directory';

    /**
     * Env var to set.
     *
     * @return mixed
     */
    protected function envVar()
    {
        return 'ACACHA_FORGE_SITE_DIRECTORY';
    }

    /**
     * Argument key.
     *
     * @return mixed
     */
    protected function argKey()
    {
        return 'site_directory';
    }

    /**
     * Question text.
     *
     * @return mixed
     */
    protected function questionText()
    {
        return 'Acacha forge site directory?';
    }

    /**
     * Default proposed value when asking.
     *
     */
    protected function default() {
        return $default = fp_env($this->envVar()) ? fp_env($this->envVar()) : $this->defaultValue();
    }

    /**
     * Default project type.
     *
     * @return string
     */
    protected function defaultValue()
    {
        return config('forge-publish.site_directory');
    }

}
