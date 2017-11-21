<?php

namespace Acacha\ForgePublish\Parser;

/**
 * Class LlumRCParser.
 *
 * @package Acacha\Llum\Parser
 */
use Acacha\ForgePublish\ForgePublishRCFile;

/**
 * Class LlumRCParser
 * @package Acacha\Llum\Parser
 */
class ForgePublishRCParser
{

    /**
     * File to parse.
     *
     * @var
     */
    protected $file;

    /**
     * LlumRCParser constructor.
     * @param $file
     */
    public function __construct(ForgePublishRCFile $file)
    {
        $this->file = $file;
    }

    /**
     * Parse llumrc file.
     *
     * @return array
     */
    public function parse()
    {
        return parse_ini_file($this->file::path());
    }

    /**
     * Get domain suffix from config file.
     *
     * @return String
     */
    public function getDomainSuffix()
    {
        $rc_file = $this->parse();
        if ( array_key_exists('domain_suffix',$rc_file)) {
            return $rc_file['domain_suffix'];
        }
    }

    /**
     * Get default shell from config file.
     *
     * @return String
     */
    public function getDefaultShell()
    {
        $rc_file = $this->parse();
        if ( array_key_exists('ssh_shell',$rc_file)) {
            return $rc_file['ssh_shell'];
        }
    }

}