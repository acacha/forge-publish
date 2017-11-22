<?php

namespace Acacha\ForgePublish\Commands\Traits;

/**
 * Trait ChecksEnv.
 * 
 * @package Acacha\ForgePublish\Commands\Traits
 */
trait ChecksEnv
{
    /**
     * Check if a argument/option exists or check for his corresponding ENV VALUE.
     *
     * @param $option
     * @param $env_var
     * @param string $type
     * @return mixed
     */
    protected function checkEnv($option, $env_var, $type = 'option')
    {
        $value = $this->$type($option) ? $this->$type($option) : env($env_var,null);
        if ( !$value) {
            $this->error("No env var $env_var found. Please run php artisan publish:init");
            die();
        }
        return $value;
    }
}