<?php

namespace Acacha\ForgePublish\Commands;

/**
 * Class PublishURL.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishURL extends SaveEnvVariable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:url {url?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set Acacha Laravel Forge API URL';

    /**
     * Env var to set.
     *
     * @return mixed
     */
    protected function envVar()
    {
        return 'ACACHA_FORGE_URL';
    }

    /**
     * Argument key.
     *
     * @return mixed
     */
    protected function argKey()
    {
        return 'url';
    }

    /**
     * Question text.
     *
     * @return mixed
     */
    protected function questionText()
    {
        return 'Acacha Forge API URL?';
    }

    /**
     * Default proposed value when asking.
     *
     */
    protected function default()
    {
        return fp_env($this->envVar()) ? fp_env($this->envVar()) : $this->getDefaultAPIURL();
    }

    /**
     * Get default API URL..
     *
     * @return string
     */
    protected function getDefaultAPIURL()
    {
        return config('forge-publish.url');
    }
}
