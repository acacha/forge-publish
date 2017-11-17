<?php

namespace Acacha\ForgePublish\Providers;

use Acacha\ForgePublish\Commands\PublishInit;
use Acacha\ForgePublish\Commands\PublishPush;
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
    }

    /**
     * Boot
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PublishInit::class,
                PublishPush::class,
            ]);
        }
    }

}