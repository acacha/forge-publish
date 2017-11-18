<?php

namespace Acacha\ForgePublish\Commands;

/**
 * Class PublishToken.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishToken extends SaveEnvVariable
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:token {token?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save Personal Access Token';

    /**
     * Env var to set.
     *
     * @return mixed
     */
    protected function envVar()
    {
        return 'ACACHA_FORGE_ACCESS_TOKEN';
    }

    /**
     * Argument key.
     *
     * @return mixed
     */
    protected function argKey()
    {
        return 'token';
    }

    /**
     * Question text.
     *
     * @return mixed
     */
    protected function questionText()
    {
        return 'Personal Access Token?';
    }

}
