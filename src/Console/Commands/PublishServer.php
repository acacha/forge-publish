<?php

namespace Acacha\ForgePublish\Commands;

/**
 * Class PublishServer.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishServer extends SaveEnvVariable
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:server {server?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save acacha forge server';

    /**
     * Env var to set.
     *
     * @return mixed
     */
    protected function envVar()
    {
        return 'ACACHA_FORGE_SERVER';
    }

    /**
     * Argument key.
     *
     * @return mixed
     */
    protected function argKey()
    {
        return 'server';
    }

    /**
     * Question text.
     *
     * @return mixed
     */
    protected function questionText()
    {
        return 'Acacha forge server (forge id)?';
    }

}
