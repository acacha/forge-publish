<?php

namespace Acacha\ForgePublish\Commands;

/**
 * Class PublishProjectType.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishProjectType extends SaveEnvVariable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:project_type {project_type?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save Acacha forge project type';

    /**
     * Env var to set.
     *
     * @return mixed
     */
    protected function envVar()
    {
        return 'ACACHA_FORGE_PROJECT_TYPE';
    }

    /**
     * Argument key.
     *
     * @return mixed
     */
    protected function argKey()
    {
        return 'project_type';
    }

    /**
     * Question text.
     *
     * @return mixed
     */
    protected function questionText()
    {
        return 'Acacha forge project type?';
    }

    /**
     * Default proposed value when asking.
     *
     */
    protected function default()
    {
        return $default = fp_env($this->envVar()) ? $this->searchProjectType(fp_env($this->envVar())) : $this->defaultValue();
    }

    /**
     * Get value.
     *
     * @return array|string
     */
    protected function value()
    {
        return $this->choice($this->questionText(), $this->getProjectTypes(), $this->default());
    }

    /**
     * Get project types.
     */
    protected function getProjectTypes()
    {
        return config('forge-publish.project_types');
    }

    /**
     * Default project type.
     *
     * @return string
     */
    protected function defaultValue()
    {
        return $this->searchProjectType();
    }

    /**
     * Search value
     */
    protected function searchProjectType($project_type = null)
    {
        if (! $project_type) {
            $project_type = config('forge-publish.project_type');
        }
        return array_search($project_type, config('forge-publish.project_types'));
    }
}
