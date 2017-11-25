<?php

namespace Acacha\ForgePublish\Commands;

/**
 * Class PublishServername.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishServername extends SaveEnvVariable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:server_name {server_name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save acacha Forge server name';

    /**
     * Env var to set.
     *
     * @return mixed
     */
    protected function envVar()
    {
        return 'ACACHA_FORGE_SERVER_NAME';
    }

    /**
     * Argument key.
     *
     * @return mixed
     */
    protected function argKey()
    {
        return 'server_name';
    }

    /**
     * Question text.
     *
     * @return mixed
     */
    protected function questionText()
    {
        return 'Acacha forge Server Name?';
    }
}
