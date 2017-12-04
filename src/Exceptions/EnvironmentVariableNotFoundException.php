<?php

namespace Acacha\ForgePublish\Exceptions;

use Exception;

/**
 * Class EnvironemntVariableNotFoundException
 * @package Acacha\ForgePublish\Exceptions
 */
class EnvironmentVariableNotFoundException extends Exception
{
    /**
     * The output returned from the operation.
     *
     * @var array
     */
    public $output;

    /**
     * Create a new exception instance.
     *
     * @return void
     */
    public function __construct($env_var)
    {
        parent::__construct('Environment variable not found: ' . $env_var);

        $this->env_var = $env_var;
    }

    /**
     * The output returned from the operation.
     *
     * @return array
     */
    public function output()
    {
        return $this->output;
    }
}
