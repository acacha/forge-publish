<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Client id
    |--------------------------------------------------------------------------
    |
    | Acacha Forge use Laravel Passport for OAuth authentication.
    | See: https://laravel.com/docs/master/passport
    |
    | Client id is provided when you run php artisan passport:install
    | (second key: "Password grant client created successfully.")
    | You could also see this value in field id of table oauth_clients
    | is the key with name Laravel Password Grant client
    |
    | This value could be overwwriten using ACACHA_FORGE_CLIENT_ID env variable
    | if you kwnow what you are doing.
    */

    'client_id' => env('ACACHA_FORGE_CLIENT_ID', 2),

    /*
    |--------------------------------------------------------------------------
    | Client secret
    |--------------------------------------------------------------------------
    |
    | Acacha Forge use Laravel Passport for OAuth authentication.
    | See: https://laravel.com/docs/master/passport
    |
    | Client secret is provided when you run php artisan passport:install
    | (second key: "Password grant client created successfully.")
    | You could also see this value in field id of table oauth_clients
    | is the key with name Laravel Password Grant client
    |
    | This value could be overwwriten using ACACHA_FORGE_CLIENT_SECRET env variable
    | if you kwnow what you are doing.
    */

    'client_secret' => env('ACACHA_FORGE_CLIENT_SECRET', '8usvRPF3DIqM7PnlH9YCyy9e1xbKbttPbesrch0F'),


   /*
   |--------------------------------------------------------------------------
   | ACACHA FORGE URL
   |--------------------------------------------------------------------------
   |
   | Acacha Forge URL: http://forge.acacha.com
   |
   | This value could be overwwriten using ACACHA_FORGE_URL env variable
   | if you kwnow what you are doing.
   */

    'url' => env('ACACHA_FORGE_URL', 'http://forge.acacha.com'),

    /*
   |--------------------------------------------------------------------------
   | ACACHA FORGE ACCESS TOKEN URI
   |--------------------------------------------------------------------------
   |
   | Acacha Forge ACCESS TOKEN URI: /oauth/token
   |
   | This value could be overwwriten using ACACHA_FORGE_ACCESS_TOKEN_URI env variable
   | if you kwnow what you are doing.
   */

    'token_uri' => env('ACACHA_FORGE_ACCESS_TOKEN_URI', '/oauth/token'),

  /*
  |--------------------------------------------------------------------------
  | ACACHA FORGE PROJECT TYPE. Default: php
  |--------------------------------------------------------------------------
  |
  | Acacha Forge Site project type: php
  |
  | This value could be overwwriten using ACACHA_FORGE_PROJECT_TYPE env variable
  | if you kwnow what you are doing.
  */

  'project_type' => env('ACACHA_FORGE_PROJECT_TYPE', 'php'),

  /*
  |--------------------------------------------------------------------------
  | ACACHA FORGE SITE DIRECTORY. Default: /public
  |--------------------------------------------------------------------------
  |
  | Acacha Forge Site directory: /public
  |
  | This value could be overwwriten using ACACHA_FORGE_SITE_DIRECTORY env variable
  | if you kwnow what you are doing.
  */

  'site_directory' => env('ACACHA_FORGE_SITE_DIRECTORY', '/public'),

];