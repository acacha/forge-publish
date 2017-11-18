<?php

namespace Acacha\ForgePublish\Commands;

/**
 * Class PublishDomain.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishDomain extends SaveEnvVariable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:domain {domain?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save acacha forge domain';

    /**
     * Env var to set.
     *
     * @return mixed
     */
    protected function envVar()
    {
        return 'ACACHA_FORGE_DOMAIN';
    }

    /**
     * Argument key.
     *
     * @return mixed
     */
    protected function argKey()
    {
        return 'domain';
    }

    /**
     * Question text.
     *
     * @return mixed
     */
    protected function questionText()
    {
        return 'Acacha forge domain?';
    }

}
