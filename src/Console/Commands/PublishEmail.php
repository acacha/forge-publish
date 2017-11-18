<?php

namespace Acacha\ForgePublish\Commands;

/**
 * Class PublishEmail.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishEmail extends SaveEnvVariable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:email {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save acacha forge email';

    /**
     * Env var to set.
     *
     * @return mixed
     */
    protected function envVar()
    {
        return 'ACACHA_FORGE_EMAIL';
    }

    /**
     * Argument key.
     *
     * @return mixed
     */
    protected function argKey()
    {
        return 'email';
    }

    /**
     * Question text.
     *
     * @return mixed
     */
    protected function questionText()
    {
        return 'Acacha forge email?';
    }
}
