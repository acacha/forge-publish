<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\AborstIfEnvVariableIsnotInstalled;
use Acacha\ForgePublish\Commands\Traits\InteractsWithEnvironment;
use Acacha\ForgePublish\Commands\Traits\InteractsWithLocalGithub;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishAssignmentUsers.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishAssignmentUsers extends Command
{
    use InteractsWithLocalGithub, InteractsWithEnvironment;

    use AborstIfEnvVariableIsnotInstalled;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:assignment_users {--user=* : The user/s assigned to this assignment}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assing users to current assignment';

    /**
     * Server names.
     *
     * @var Client
     */
    protected $http;

    /**
     * Users.
     *
     * @var array
     */
    protected $users;

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

        $this->users = $this->option('user') ? $this->argument('user') : $this->askForUsers();

        if (count($this->users) == 0) {
            $this->info('Skipping users...');
            return;
        }
        $this->assignUsersToAssignment();

    }

    /**
     * Assign users to assignment
     *
     * @return array|mixed
     */
    protected function assignUsersToAssignment()
    {
        $assignment = fp_env('ACACHA_FORGE_ASSIGNMENT');
        foreach ( $this->users as $user) {
            $uri = str_replace('{assignment}', $assignment, config('forge-publish.assign_user_to_assignment_uri'));
            $uri = str_replace('{user}', $user, $uri);
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
                    $this->error('The user is already assigned');
                    return;
                }
                $this->error('And error occurs connecting to the api url: ' . $url);
                $this->error('Status code: ' . $e->getResponse()->getStatusCode() . ' | Reason : ' . $e->getResponse()->getReasonPhrase());
                return;
            }
        }
    }

    /**
     * Get users
     */
    protected function users()
    {
        $url = config('forge-publish.url') . config('forge-publish.list_users_uri');
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
     * Ask for users.
     *
     * @return string
     */
    protected function askForUsers()
    {
        $default = 0;
        $users = $this->users();
        $user_names = array_merge(
            ['Skip'],
            collect($users)->pluck('name')->toArray()
        );

        $selected_user_names =  $this->choice('Users?', $user_names ,$default, null, true);

        if ($selected_user_names == 0) return null;
        $users = collect($users)->filter(function ($user) use ($selected_user_names) {
            return in_array($user->name,$selected_user_names);
        });

        return $users->pluck('id');
    }

    /**
     * Abort command execution.
     */
    protected function abortCommandExecution()
    {
        $this->abortsIfEnvVarIsNotInstalled('ACACHA_FORGE_ASSIGNMENT');
    }
}
