<?php

namespace Acacha\ForgePublish\Commands;
use Acacha\ForgePublish\Parser\ForgePublishRCParser;

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
     * ForgePublishRCParser
     *
     * @var ForgePublishRCParser
     */
    protected $parser;

    /**
     * SaveEnvVariable constructor.
     *
     */
    public function __construct(ForgePublishRCParser $parser)
    {
        parent::__construct();
        $this->parser = $parser;
    }

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

    /**
     * Default proposed value when asking.
     *
     */
    protected function default() {
        return $default = fp_env('ACACHA_FORGE_DOMAIN') ? fp_env('ACACHA_FORGE_DOMAIN') : $this->defaultDomain();
    }

    /**
     * Default domain.
     *
     * @return string
     */
    protected function defaultDomain()
    {
        if ($suffix = $this->parser->getDomainSuffix()) return strtolower(camel_case(basename(getcwd()))) . '.' . $suffix;
        return '';
    }

}
