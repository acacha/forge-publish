<?php

use josegonzalez\Dotenv\Loader;

if (! function_exists('fp_env')) {

    /**
     * Helper test.
     */
    function fp_env($variable, $default = null)
    {
        //NOTE: We cannot use env() helper because the .env file has been changes in this request !!!
        $env = (new Loader(base_path('.env')))->parse()->toArray();
        return array_key_exists($variable, $env) ? $env[$variable] : $default;
    }
}
