<?php

namespace Acacha\ForgePublish\Providers;

use Acacha\ForgePublish\Commands\Publish;
use Acacha\ForgePublish\Commands\PublishArtisan;
use Acacha\ForgePublish\Commands\PublishAutodeploy;
use Acacha\ForgePublish\Commands\PublishCheckToken;
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
use Acacha\ForgePublish\Commands\PublishRepository;
use Acacha\ForgePublish\Commands\PublishIp;
use Acacha\ForgePublish\Commands\PublishKeyGenerate;
use Acacha\ForgePublish\Commands\PublishLog;
use Acacha\ForgePublish\Commands\PublishLogin;
use Acacha\ForgePublish\Commands\PublishNpm;
use Acacha\ForgePublish\Commands\PublishOpen;
use Acacha\ForgePublish\Commands\PublishProjectType;
use Acacha\ForgePublish\Commands\PublishRc;
use Acacha\ForgePublish\Commands\PublishServer;
use Acacha\ForgePublish\Commands\PublishServername;
use Acacha\ForgePublish\Commands\PublishSite;
use Acacha\ForgePublish\Commands\PublishSiteDirectory;
use Acacha\ForgePublish\Commands\PublishSites;
use Acacha\ForgePublish\Commands\PublishSSH;
use Acacha\ForgePublish\Commands\PublishSSL;
use Acacha\ForgePublish\Commands\PublishToken;
use Acacha\ForgePublish\Commands\PublishURL;
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
                PublishCheckToken::class,
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
                PublishIp::class,
                PublishKeyGenerate::class,
                PublishLog::class,
                PublishLogin::class,
                PublishNpm::class,
                PublishOpen::class,
                PublishProjectType::class,
                Publish::class,
                PublishRc::class,
                PublishRepository::class,
                PublishServer::class,
                PublishServername::class,
                PublishSiteDirectory::class,
                PublishSite::class,
                PublishSites::class,
                PublishSSH::class,
                PublishSSL::class,
                PublishToken::class,
                PublishURL::class,
            ]);
        }
        
        $this->publishConfig();
    }

    /**
     * Publish config.
     */
    protected function publishConfig()
    {
        $this->publishes([
            ACACHA_FORGE_PUBLISH_PATH .'/config/forge-publish.php' => config_path('forge-publish.php'),
        ]);
    }

}