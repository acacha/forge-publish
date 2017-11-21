<?php

namespace Acacha\ForgePublish\Commands;

/**
 * Class PublishIp.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishIp extends SaveEnvVariable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:ip {ip_address?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save acacha forge ip';

    /**
     * Env var to set.
     *
     * @return mixed
     */
    protected function envVar()
    {
        return 'ACACHA_FORGE_IP_ADDRESS';
    }

    /**
     * Argument key.
     *
     * @return mixed
     */
    protected function argKey()
    {
        return 'ip_address';
    }

    /**
     * Question text.
     *
     * @return mixed
     */
    protected function questionText()
    {
        return 'Acacha forge ip?';
    }
}
