<?php

namespace Acacha\ForgePublish\Providers;

use Acacha\ForgePublish\Commands\PublishArtisan;
use Acacha\ForgePublish\Commands\PublishAutodeploy;
use Acacha\ForgePublish\Commands\PublishComposer;
use Acacha\ForgePublish\Commands\PublishConnect;
use Acacha\ForgePublish\Commands\PublishCreateSite;
use Acacha\ForgePublish\Commands\PublishDNS;
use Acacha\ForgePublish\Commands\PublishDomain;
use Acacha\ForgePublish\Commands\PublishEmail;
use Acacha\ForgePublish\Commands\PublishEnv;
use Acacha\ForgePublish\Commands\PublishGit;
use Acacha\ForgePublish\Commands\PublishInfo;
use Acacha\ForgePublish\Commands\PublishInit;
use Acacha\ForgePublish\Commands\PublishInstall;
use Acacha\ForgePublish\Commands\PublishInstallRepo;
use Acacha\ForgePublish\Commands\PublishIp;
use Acacha\ForgePublish\Commands\PublishKeyGenerate;
use Acacha\ForgePublish\Commands\PublishLog;
use Acacha\ForgePublish\Commands\PublishLogin;
use Acacha\ForgePublish\Commands\PublishNpm;
use Acacha\ForgePublish\Commands\PublishOpen;
use Acacha\ForgePublish\Commands\PublishPush;
use Acacha\ForgePublish\Commands\PublishRc;
use Acacha\ForgePublish\Commands\PublishServer;
use Acacha\ForgePublish\Commands\PublishSite;
use Acacha\ForgePublish\Commands\PublishSsh;
use Acacha\ForgePublish\Commands\PublishSSL;
use Acacha\ForgePublish\Commands\PublishToken;
use Illuminate\Support\ServiceProvider;

/**
 * Class AcachaForgePublishServiceProvider.
 */
class AcachaForgePublishServiceProvider extends ServiceProvider
{

    public function register()
    {
        if (!defined('ACACHA_FORGE_PUBLISH_PATH')) {
            define('ACACHA_FORGE_PUBLISH_PATH', realpath(__DIR__.'/../../'));
        }

        $this->mergeConfigFrom(
            ACACHA_FORGE_PUBLISH_PATH.'/config/forge-publish.php', 'forge-publish'
        );
    }

    /**
     * Boot
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PublishArtisan::class,
                PublishAutodeploy::class,
                PublishComposer::class,
                PublishConnect::class,
                PublishCreateSite::class,
                PublishDNS::class,
                PublishDomain::class,
                PublishEmail::class,
                PublishEnv::class,
                PublishGit::class,
                PublishInfo::class,
                PublishInit::class,
                PublishInstall::class,
                PublishInstallRepo::class,
                PublishIp::class,
                PublishKeyGenerate::class,
                PublishLog::class,
                PublishLogin::class,
                PublishNpm::class,
                PublishOpen::class,
                PublishPush::class,
                PublishRc::class,
                PublishServer::class,
                PublishSite::class,
                PublishSsh::class,
                PublishSSL::class,
                PublishToken::class,
            ]);
        }
        
        $this->publishConfig();
    }

    protected function publishConfig()
    {
        $this->publishes([
            ACACHA_FORGE_PUBLISH_PATH .'/config/forge-publish.php' => config_path('forge-publish.php'),
        ]);
    }

}