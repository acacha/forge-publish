<?php

namespace Acacha\ForgePublish\Commands;

use Illuminate\Console\Command;

/**
 * Class PublishInit.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Config publish command';

    /**
     * Create a new command instance.
     *
     * @return void
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
        $this->info('Hello! We are going to config Acacha Laravel Forge publish...');
        $this->info('Please go to https://forge.acacha.org/passport-tokens an create a Personal Access Token');

        $token = $this->ask('Personal Access Token?');

        // WRITE TOKEN TO .env file
        $this->filesystem->overwrite(
            (new LlumRCFile())->path(),
            $this->compiler->compile(
                $this->filesystem->get($this->getStubPath()),
                $this->data));


    }
}
