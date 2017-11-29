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

    'url' => env('ACACHA_FORGE_URL', 'https://forge.acacha.org'),

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
  | ACACHA FORGE PROJECT TYPES.
  |--------------------------------------------------------------------------
  |
  | Acacha Forge Site project types
  |
  */

  'project_types' => [
      'php',
      'html',
      'Symfony',
      'Symfony_dev'
  ],

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

  'post_lets_encrypt_uri' => env('ACACHA_FORGE_POST_LETS_ENCRYPT_URI', '/api/v1/user/servers/{forgeserver}/sites/{forgesite}/certificates/letsencrypt'),

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

  'post_auto_deploy_uri' => env('ACACHA_FORGE_POST_AUTO_DEPLOY_URI', '/api/v1/user/servers/{forgeserver}/sites/{forgesite}/deploy'),

    /*
  |--------------------------------------------------------------------------
  | ACACHA FORGE GET CHECK TOKEN URI ENDPOINT. Default: /api/v1/check_token
  |--------------------------------------------------------------------------
  |
  | Acacha Forge get check token URI endpoint: /api/v1/check_token
  |
  | This value could be overwriten using ACACHA_FORGE_GET_CHECK_TOKEN_URI env variable
  | if you kwnow what you are doing.
  */

  'get_check_token_uri' => env('ACACHA_FORGE_GET_CHECK_TOKEN_URI', '/api/v1/check_token'),


  /*
  |--------------------------------------------------------------------------
  | ACACHA FORGE GET CERTIFICATES URI ENDPOINT. Default: /api/v1/user/servers/{forgeserver}/sites/{forgesite}/certificates
  |--------------------------------------------------------------------------
  |
  | Acacha Forge get certificates URI endpoint: /api/v1/user/servers/{forgeserver}/sites/{forgesite}/certificates
  |
  | This value could be overwriten using ACACHA_FORGE_GET_CERTIFICATES_URI env variable
  | if you kwnow what you are doing.
  */

  'get_certificates_uri' => env('ACACHA_FORGE_GET_CERTIFICATES_URI', '/api/v1/user/servers/{forgeserver}/sites/{forgesite}/certificates'),


  /*
  |--------------------------------------------------------------------------
  | ACACHA FORGE POST DEPLOY SITE URI ENDPOINT. Default: /api/v1/user/servers/{forgeserver}/sites/{forgesite}/deployment/deploy
  |--------------------------------------------------------------------------
  |
  | Acacha Forge post certificates URI endpoint: /api/v1/user/servers/{forgeserver}/sites/{forgesite}/deployment/deploy
  |
  | This value could be overwriten using ACACHA_FORGE_POST_DEPLOY_SITE_URI env variable
  | if you kwnow what you are doing.
  */
  'post_deploy_site_uri' => env('ACACHA_FORGE_POST_DEPLOY_SITE_URI', '/api/v1/user/servers/{forgeserver}/sites/{forgesite}/deployment/deploy'),

  /*
  |--------------------------------------------------------------------------
  | ACACHA FORGE POST MYSQL URI ENDPOINT. Default: /api/v1/user/servers/{forgeserver}/mysql
  |--------------------------------------------------------------------------
  |
  | Acacha Forge post mysql URI endpoint: /api/v1/user/servers/{forgeserver}/mysql
  |
  | This value could be overwriten using ACACHA_FORGE_POST_MYSQL_URI env variable
  | if you kwnow what you are doing.
  */
  'post_mysql_uri' => env('ACACHA_FORGE_POST_MYSQL_URI', '/api/v1/user/servers/{forgeserver}/mysql'),


  /*
  |--------------------------------------------------------------------------
  | ACACHA FORGE GET MYSQL URI ENDPOINT. Default: /api/v1/user/servers/{forgeserver}/mysql
  |--------------------------------------------------------------------------
  |
  | Acacha Forge get mysql URI endpoint: /api/v1/user/servers/{forgeserver}/mysql
  |
  | This value could be overwriten using ACACHA_FORGE_GET_MYSQL_URI env variable
  | if you kwnow what you are doing.
  */
  'get_mysql_uri' => env('ACACHA_FORGE_GET_MYSQL_URI', '/api/v1/user/servers/{forgeserver}/mysql'),

    /*
  |--------------------------------------------------------------------------
  | ACACHA FORGE POST MYSQL USERS URI ENDPOINT. Default: /api/v1/user/servers/{forgeserver}/mysql_users
  |--------------------------------------------------------------------------
  |
  | Acacha Forge post mysql users URI endpoint: /api/v1/user/servers/{forgeserver}/mysql_users
  |
  | This value could be overwriten using ACACHA_FORGE_POST_MYSQL_USERS_URI env variable
  | if you kwnow what you are doing.
  */
    'post_mysql_users_uri' => env('ACACHA_FORGE_POST_MYSQL_USERS_URI', '/api/v1/user/servers/{forgeserver}/mysql_users'),


    /*
    |--------------------------------------------------------------------------
    | ACACHA FORGE GET MYSQL USERS URI ENDPOINT. Default: /api/v1/user/servers/{forgeserver}/mysql_users
    |--------------------------------------------------------------------------
    |
    | Acacha Forge get mysql users URI endpoint: /api/v1/user/servers/{forgeserver}/mysql_users
    |
    | This value could be overwriten using ACACHA_FORGE_GET_MYSQL_USERS_URI env variable
    | if you kwnow what you are doing.
    */
    'get_mysql_users_uri' => env('ACACHA_FORGE_GET_MYSQL_USERS_URI', '/api/v1/user/servers/{forgeserver}/mysql_users'),

    /*
    |--------------------------------------------------------------------------
    | ACACHA FORGE SHOW DEPLOYMENT SCRIPT URI ENDPOINT. Default: /api/v1/user/servers/{forgeserver}/sites/{forgesite}deployment/script
    |--------------------------------------------------------------------------
    |
    | Acacha Forge show mysql users URI endpoint: /api/v1/user/servers/{forgeserver}/sites/{forgesite}deployment/script
    |
    | This value could be overwriten using ACACHA_FORGE_SHOW_DEPLOYMENT_SCRIPT_URI env variable
    | if you kwnow what you are doing.
    */
    'show_deployment_script_uri' => env('ACACHA_FORGE_SHOW_DEPLOYMENT_SCRIPT_URI', '/api/v1/user/servers/{forgeserver}/sites/{forgesite}/deployment/script'),

    /*
    |--------------------------------------------------------------------------
    | ACACHA FORGE UPDATE DEPLOYMENT SCRIPT URI ENDPOINT. Default: /api/v1/user/servers/{forgeserver}/sites/{forgesite}deployment/script
    |--------------------------------------------------------------------------
    |
    | Acacha Forge update mysql users URI endpoint: /api/v1/user/servers/{forgeserver}/sites/{forgesite}deployment/script
    |
    | This value could be overwriten using ACACHA_FORGE_UPDATE_DEPLOYMENT_URI env variable
    | if you kwnow what you are doing.
    */
    'update_deployment_script_uri' => env('ACACHA_FORGE_UPDATE_DEPLOYMENT_URI', '/api/v1/user/servers/{forgeserver}/sites/{forgesite}/deployment/script'),

];
