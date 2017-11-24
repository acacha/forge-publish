<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\InteractsWithEnvironment;
use Acacha\ForgePublish\Commands\Traits\DiesIfNoEnvFileExists;
use Illuminate\Console\Command;

/**
 * Class SaveEnvVariable.
 *
 * @package Acacha\ForgePublish\Commands
 */
abstract class SaveEnvVariable extends Command
{
    use DiesIfNoEnvFileExists, InteractsWithEnvironment;

    /**
     * Env var to set.
     *
     * @return mixed
     */
    protected abstract function envVar();

    /**
     * Argument key.
     *
     * @return mixed
     */
    protected abstract function argKey();

    /**
     * Question text.
     *
     * @return mixed
     */
    protected abstract function questionText();

    /**
     * SaveEnvVariable constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->checkIfCommandHaveToBeSkipped();

        $this->before();

        $value = $this->argument($this->argKey()) ? $this->argument($this->argKey()) : $this->value() ;
        $this->addValueToEnv($this->envVar(), $value);

        $this->info('The Acacha Forge ' . $this->argKey() . ' has been added to file .env with key ' . $this->envVar());

        $this->after();
    }

    /**
     * Before.
     */
    protected function before() {
        //
    }

    /**
     * After.
     */
    protected function after() {
        //
    }

    /**
     * Get value.
     *
     * @return array|string
     */
    protected function value()
    {
        return $this->ask($this->questionText(), $this->default());
    }

    /**
     * Check if command have to be skipped.
     */
    protected function checkIfCommandHaveToBeSkipped()
    {
        $this->dieIfNoEnvFileIsFound();
    }

    /**
     * Default.
     */
    protected function default() {
        return '';
    }

}
