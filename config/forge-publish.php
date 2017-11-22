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
    | This value could be overwritted using ACACHA_FORGE_CLIENT_ID env variable
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
    | This value could be overwritted using ACACHA_FORGE_CLIENT_SECRET env variable
    | if you kwnow what you are doing.
    */

    'client_secret' => env('ACACHA_FORGE_CLIENT_SECRET', 'dLdsIf3nPMWJC4gOCNcsUn5pBSv5tTPSaU51Gu2F'),


   /*
   |--------------------------------------------------------------------------
   | ACACHA FORGE URL
   |--------------------------------------------------------------------------
   |
   | Acacha Forge URL: http://forge.acacha.com
   |
   | This value could be overwritted using ACACHA_FORGE_URL env variable
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
   | This value could be overwritted using ACACHA_FORGE_ACCESS_TOKEN_URI env variable
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
  | This value could be overwritted using ACACHA_FORGE_PROJECT_TYPE env variable
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
  | This value could be overwritted using ACACHA_FORGE_SITE_DIRECTORY env variable
  | if you kwnow what you are doing.
  */

  'site_directory' => env('ACACHA_FORGE_SITE_DIRECTORY', '/public'),

  /*
  |--------------------------------------------------------------------------
  | ACACHA FORGE USER SERVERS URI ENDPOINT. Default: /api/v1/user/servers
  |--------------------------------------------------------------------------
  |
  | Acacha Forge user servers URI endpoint: /api/v1/user/servers
  |
  | This value could be overwriten using ACACHA_FORGE_USER_SERVERS_URI env variable
  | if you kwnow what you are doing.
  */

  'user_servers_uri' => env('ACACHA_FORGE_USER_SERVERS_URI', '/api/v1/user/servers'),

  /*
  |--------------------------------------------------------------------------
  | ACACHA FORGE USER SITES URI ENDPOINT. Default: /api/v1//user/servers
  |--------------------------------------------------------------------------
  |
  | Acacha Forge user servers URI endpoint: /api/v1//user/servers
  |
  | This value could be overwriten using ACACHA_FORGE_USER_SITES_URI env variable
  | if you kwnow what you are doing.
  */

  'user_sites_uri' => env('ACACHA_FORGE_USER_SITES_URI', '/api/v1/user/sites'),

  /*
  |--------------------------------------------------------------------------
  | ACACHA FORGE POST SITES URI ENDPOINT. Default: /api/v1/servers/{forgeserver}/sites
  |--------------------------------------------------------------------------
  |
  | Acacha Forge post site URI endpoint: /api/v1/servers/{forgeserver}/sites
  |
  | This value could be overwriten using ACACHA_FORGE_POST_SITES_URI env variable
  | if you kwnow what you are doing.
  */

  'post_sites_uri' => env('ACACHA_FORGE_POST_SITES_URI', '/api/v1/servers/{forgeserver}/sites'),

  /*
  |--------------------------------------------------------------------------
  | ACACHA FORGE POST GIT REPOSITORY URI ENDPOINT. Default: /api/v1/user/servers/{forgeserver}/sites/{forgesite}/git
  |--------------------------------------------------------------------------
  |
  | Acacha Forge post git repository URI endpoint: /api/v1/user/servers/{forgeserver}/sites/{forgesite}/git
  |
  | This value could be overwriten using ACACHA_FORGE_POST_GIT_REPOSITORY_URI env variable
  | if you kwnow what you are doing.
  */
  'post_git_repository_uri' => env('ACACHA_FORGE_POST_GIT_REPOSITORY_URI', '/api/v1/user/servers/{forgeserver}/sites/{forgesite}/git'),

  /*
  |--------------------------------------------------------------------------
  | ACACHA FORGE POST SSH KEYS URI ENDPOINT. Default: /api/v1/servers/{forgeserver}/keys
  |--------------------------------------------------------------------------
  |
  | Acacha Forge post SSH keys URI endpoint: /api/v1/servers/{forgeserver}/keys
  |
  | This value could be overwriten using ACACHA_FORGE_POST_SSH_KEYS_URI env variable
  | if you kwnow what you are doing.
  */

  'post_ssh_keys_uri' => env('ACACHA_FORGE_POST_SSH_KEYS_URI', '/api/v1/user/servers/{forgeserver}/keys'),

  /*
  |--------------------------------------------------------------------------
  | ACACHA FORGE POST LETS ENCRYPT URI ENDPOINT. Default: /api/v1/user/servers/{forgeserver}/sites/{forgesite}/certificates/letsencrypt
  |--------------------------------------------------------------------------
  |
  | Acacha Forge post lets encrypt URI endpoint: /api/v1/user/servers/{forgeserver}/sites/{forgesite}/certificates/letsencrypt
  |
  | This value could be overwriten using ACACHA_FORGE_POST_LETS_ENCRYPT_URI env variable
  | if you kwnow what you are doing.
  */

  'post_lets_encrypt_uri' => env('ACACHA_FORGE_POST_LETS_ENCRYPT_URI','/api/v1/user/servers/{forgeserver}/sites/{forgesite}/certificates/letsencrypt'),

  /*
  |--------------------------------------------------------------------------
  | ACACHA FORGE POST AUTO DEPLOY URI ENDPOINT. Default: /api/v1/user/servers/{serverId}/sites/{siteId}/deploy
  |--------------------------------------------------------------------------
  |
  | Acacha Forge post auto deploy URI endpoint: /api/v1/user/servers/{serverId}/sites/{siteId}/deploy
  |
  | This value could be overwriten using ACACHA_FORGE_POST_AUTO_DEPLOY_URI env variable
  | if you kwnow what you are doing.
  */

  'post_auto_deploy_uri' => env('ACACHA_FORGE_POST_AUTO_DEPLOY_URI','/api/v1/user/servers/{forgeserver}/sites/{forgesite}/deploy'),

];
