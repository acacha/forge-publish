<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\AborstIfEnvVariableIsnotInstalled;
use Acacha\ForgePublish\Commands\Traits\InteractsWithEnvironment;
use Acacha\ForgePublish\Commands\Traits\InteractsWithLocalGithub;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishAssignmentGroups.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishAssignmentGroups extends Command
{
    use InteractsWithLocalGithub, InteractsWithEnvironment;

    use AborstIfEnvVariableIsnotInstalled;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:assignment_groups {--group=* : The group/s assigned to this assignment}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a group of users to the current assignment';

    /**
     * Server names.
     *
     * @var Client
     */
    protected $http;

    /**
     * Groups
     *
     * @var array
     */
    protected $groups;

    /**
     * SaveEnvVariable constructor.
     *
     */
    public function __construct(Client $http)
    {
        parent::__construct();
        $this->http = $http;
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $this->abortCommandExecution();

        $this->groups = $this->option('group') ? $this->argument('group') : $this->askForGroups();
        $this->assignGroupsToAssignment();
    }

    /**
     * Assign groups to assignment
     *
     * @return array|mixed
     */
    protected function assignGroupsToAssignment()
    {
        $assignment = fp_env('ACACHA_FORGE_ASSIGNMENT');
        foreach ( $this->groups as $group) {
            $uri = str_replace('{assignment}', $assignment, config('forge-publish.assign_group_to_assignment_uri'));
            $uri = str_replace('{group}', $group, $uri);
            $url = config('forge-publish.url') . $uri;
            try {
                $response = $this->http->post($url, [
                    'headers' => [
                        'X-Requested-With' => 'XMLHttpRequest',
                        'Authorization' => 'Bearer ' . fp_env('ACACHA_FORGE_ACCESS_TOKEN')
                    ]
                ]);
            } catch (\Exception $e) {
                if ($e->getResponse()->getStatusCode() == 422) {
                    $this->error('The group is already assigned');
                    return;
                }
                $this->error('And error occurs connecting to the api url: ' . $url);
                $this->error('Status code: ' . $e->getResponse()->getStatusCode() . ' | Reason : ' . $e->getResponse()->getReasonPhrase());
                return [];
            }
        }
    }

    /**
     * Ask for groups.
     *
     * @return string
     */
    protected function askForGroups()
    {
        $default = 0;
        $groups = $this->groups();
        $group_names = collect($groups)->pluck('name')->toArray();
        $selected_group_names =  $this->choice('Groups?', $group_names ,$default, null, true);

        $groups = collect($groups)->filter(function ($group) use ($selected_group_names) {
            return in_array($group->name,$selected_group_names);
        });

        return $groups->pluck('id');
    }

    /**
     * Get groups
     */
    protected function groups()
    {
        $url = config('forge-publish.url') . config('forge-publish.list_groups_uri');
        try {
            $response = $this->http->get($url, [
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . fp_env('ACACHA_FORGE_ACCESS_TOKEN')
                ]
            ]);
        } catch (\Exception $e) {
            $this->error('And error occurs connecting to the api url: ' . $url);
            $this->error('Status code: ' . $e->getResponse()->getStatusCode() . ' | Reason : ' . $e->getResponse()->getReasonPhrase());
            return [];
        }
        return json_decode((string) $response->getBody());
    }

    /**
     * Abort command execution.
     */
    protected function abortCommandExecution()
    {
        $this->abortsIfEnvVarIsNotInstalled('ACACHA_FORGE_ASSIGNMENT');
    }
}
