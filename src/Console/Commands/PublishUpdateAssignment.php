<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\AborstIfEnvVariableIsnotInstalled;
use Acacha\ForgePublish\Commands\Traits\InteractsWithAssignments;
use Acacha\ForgePublish\Commands\Traits\InteractsWithEnvironment;
use Acacha\ForgePublish\Commands\Traits\InteractsWithLocalGithub;
use Acacha\ForgePublish\Commands\Traits\ItFetchesAssignments;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishUpdateAssignment.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishUpdateAssignment extends Command
{
    use InteractsWithEnvironment, AborstIfEnvVariableIsnotInstalled, InteractsWithAssignments, ItFetchesAssignments;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:update_assignment {assignment? : The assignment to update}
                                                        {name? : The name description}
                                                        {repository_uri? : The repository URI}
                                                        {repository_type? : The repository type}
                                                        {forge_site? : The Laravel Forge site id}
                                                        {forge_server? : The Laravel Forge Server}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates an assignment';

    /**
     * Server names.
     *
     * @var Client
     */
    protected $http;

    /**
     * Assignments.
     *
     * @var []
     */
    protected $assignments;

    /**
     * Assignment.
     *
     * @var integer
     */
    protected $assignment;

    /**
     * Assignment name
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

        $this->assignment = $this->argument('assignment') ? $this->argument('assignment') : $this->askAssignment();
        $this->assignmentName = $this->argument('name') ? $this->argument('name') : $this->askName();
        $this->repository_uri = $this->argument('repository_uri') ? $this->argument('repository_uri') : $this->askRepositoryUri();
        $this->repository_type = $this->argument('repository_type') ? $this->argument('repository_type') : $this->askRepositoryType();
        $this->forge_site = $this->argument('forge_site') ? $this->argument('forge_site') : $this->askForgeSite();
        $this->forge_server = $this->argument('forge_server') ? $this->argument('forge_server') : $this->askForgeServer();

        $this->updateAssignment();
    }

    /**
     * Update assignment.
     *
     * @return array|mixed
     */
    protected function updateAssignment()
    {
        $uri = str_replace('{assignment}', $this->assignment, config('forge-publish.update_assignment_uri'));
        $url = config('forge-publish.url') . $uri;
        try {
            $response = $this->http->put($url, [
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
            return [];
        }
        return json_decode((string) $response->getBody());
    }

    /**
     * Abort command execution.
     */
    protected function abortCommandExecution()
    {
        $this->abortsIfEnvVarIsNotInstalled('ACACHA_FORGE_ACCESS_TOKEN');
    }
}
