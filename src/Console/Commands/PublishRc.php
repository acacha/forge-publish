<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Compiler\RCFileCompiler;
use Acacha\ForgePublish\ForgePublishRCFile;
use Illuminate\Console\Command;

/**
 * Class PublishRc.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishRc extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:rc {domain_suffix?} {ssh_shell?} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Forge publish rc (~/.forgepublishrc) config file';

    /**
     * Compiler for llumrc file.
     *
     * @var RCFileCompiler
     */
    protected $compiler;

    /**
     * Constructor.
     *
     */
    public function __construct(RCFileCompiler $compiler)
    {
        parent::__construct();
        $this->compiler = $compiler;
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $domain_suffix = $this->argument('domain_suffix') ?
            $this->argument('domain_suffix') :
            $this->ask('Default domain suffix?');

        $ssh_shell = $this->argument('ssh_shell') ?
            $this->argument('ssh_shell') :
            $this->choice('Shell to use in SSH?',['bash','zsh'],0);

        $data = [
            "ACACHA_FORGE_PUBLISH_DOMAIN_PREFIX" => $domain_suffix,
            "ACACHA_FORGE_PUBLISH_SSH_SHELL" => $ssh_shell,
        ];

        $content = $this->compiler->compile(
            file_get_contents($this->getStubPath()),
            $data);
        return file_put_contents(ForgePublishRCFile::path(), $content);

    }

    /**
     * Get path to stub.
     *
     * @return string
     */
    protected function getStubPath() {
        return __DIR__ . '/stubs/forgepublishrc.stub';
    }

}
