<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\InteractsWithEnvironment;
use Acacha\ForgePublish\Commands\Traits\SkipsIfNoEnvFileExists;
use Acacha\ForgePublish\Commands\Traits\SkipsIfTokenIsAlreadyInstalled;
use Illuminate\Console\Command;

/**
 * Class PublishToken.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishToken extends Command
{
    use SkipsIfNoEnvFileExists, SkipsIfTokenIsAlreadyInstalled, InteractsWithEnvironment;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:token {token?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save Personal Access Token';

    /**
     * PublishToken constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->checkIfCommandHaveToBeSkipped();

        $this->info('Please go to http://forge.acacha.org/passport-tokens and create a Personal Access Token. Please copy the token...');

        $token = $this->argument('token') ? $this->argument('token') : $this->ask('Personal Access Token?');
        $this->addValueToEnv('ACACHA_FORGE_ACCESS_TOKEN', $token);

        $this->info('The access token has been added to file .env with key ACACHA_FORGE_ACCESS_TOKEN');
    }

    /**
     * Check if command have to be skipped.
     */
    protected function checkIfCommandHaveToBeSkipped()
    {
        $this->skipIfNoEnvFileIsFound();
        $this->skipIfTokenIsAlreadyInstalled();
    }

}
