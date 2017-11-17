<?php

namespace Acacha\ForgePublish\Commands;

use Illuminate\Console\Command;

/**
 * Class PublishPush.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishPush extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:push';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish to Forge';

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
        $this->info('All pending to be done');
    }
}
