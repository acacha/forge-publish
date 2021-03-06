<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\AborstIfEnvVariableIsnotInstalled;
use Acacha\ForgePublish\Commands\Traits\InteractsWithAssignments;
use Acacha\ForgePublish\Commands\Traits\InteractsWithEnvironment;
use Acacha\ForgePublish\Commands\Traits\InteractsWithLocalGithub;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishAssignment.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishAssignment extends Command
{

    use InteractsWithLocalGithub, InteractsWithEnvironment, AborstIfEnvVariableIsnotInstalled, InteractsWithAssignments;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:assignment {name? : The name description} 
                                               {repository_uri? : The repository URI} 
                                               {repository_type? : The repository type} 
                                               {forge_site? : The Laravel Forge site id} 
                                               {forge_server? : The Laravel Forge Server}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new assignment using current project';

    /**
     * Server names.
     *
     * @var Client
     */
    protected $http;

    /**
     * Assignment name.
     *
     * @var String
     */
    protected $assignmentName;

    /**
     * Repository uri.
     *
     * @var String
     */
    protected $repository_uri;

    /**
     * Repository type.
     *
     * @var String
     */
    protected $repository_type;

    /**
     * Laravel Forge site id.
     *
     * @var String
     */
    protected $forge_site;

    /**
     * Existing assignment
     * @var
     */
    protected $existingAssignment;

    /**
     * Laravel Forge server id.
     *
     * @var String
     */
    protected $forge_server;

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

        $this->existingAssignment = fp_env('ACACHA_FORGE_ASSIGNMENT');

        if ($this->existingAssignment) $this->error('An assignment with id : ' . $this->existingAssignment . ' already exists! Updating...' );

        $this->assignmentName = $this->argument('name') ? $this->argument('name') : $this->askName();
        $this->repository_uri = $this->argument('repository_uri') ? $this->argument('repository_uri') : $this->askRepositoryUri();
        $this->repository_type = $this->argument('repository_type') ? $this->argument('repository_type') : $this->askRepositoryType();
        $this->forge_site = $this->argument('forge_site') ? $this->argument('forge_site') : $this->askForgeSite();
        $this->forge_server = $this->argument('forge_server') ? $this->argument('forge_server') : $this->askForgeServer();

        if (! $this->existingAssignment) {
            $this->createAssignment();
            $this->info('Assignment created ok!');
        } else {
            $this->call('publish:update_assignment', [
               'assignment' => $this->existingAssignment,
               'name' => $this->assignmentName,
               'repository_uri' => $this->repository_uri,
               'repository_type' => $this->repository_type,
               'forge_site' => $this->forge_site,
               'forge_server' => $this->forge_server,
            ]);
        }

        $this->call('publish:assignment_groups');
        $this->call('publish:assignment_users');
    }

    /**
     * Create assignment.
     *
     * @return array|mixed
     */
    protected function createAssignment()
    {
        $url = config('forge-publish.url') . config('forge-publish.store_assignment_uri');
        try {
            $response = $this->http->post($url, [
                'form_params' => [
                    'name' => $this->assignmentName,
                    'repository_uri' => $this->repository_uri,
                    'repository_type' => $this->repository_type,
                    'forge_site' => $this->forge_site,
                    'forge_server' => $this->forge_server
                ],
                'headers' => [
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Authorization' => 'Bearer ' . fp_env('ACACHA_FORGE_ACCESS_TOKEN')
                ]
            ]);
        } catch (\Exception $e) {
            $this->error('And error occurs connecting to the api url: ' . $url);
            $this->error('Status code: ' . $e->getResponse()->getStatusCode() . ' | Reason : ' . $e->getResponse()->getReasonPhrase());
            return;
        }
        $assignment = json_decode((string) $response->getBody());
        $this->addValueToEnv('ACACHA_FORGE_ASSIGNMENT', $assignment->id);
        return $assignment;
    }

    /**
     * Abort command execution.
     */
    protected function abortCommandExecution()
    {
        $this->abortsIfEnvVarIsNotInstalled('ACACHA_FORGE_ACCESS_TOKEN');
    }
}
