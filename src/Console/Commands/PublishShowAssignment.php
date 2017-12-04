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
 * Class PublishShowAssignment.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishShowAssignment extends Command
{
    use InteractsWithEnvironment,
        AborstIfEnvVariableIsnotInstalled,
        InteractsWithAssignments,
        ItFetchesAssignments;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:show_assignment {assignment? : The assignment to show}' ;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows an assignment';

    /**
     * Assignment.
     *
     * @var integer
     */
    protected $assignment;

    /**
     * Server names.
     *
     * @var Client
     */
    protected $http;

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

        $this->show_assignment();
    }

    /**
     * Show assignment.
     */
    protected function show_assignment() {
        $assignment = $this->fetch_assignment();

        $headers = ['Key', 'Value'];

        $rows = [
            [ 'Id' , $assignment->id],
            [ 'Name' , $assignment->name],
            [ 'Repository uri' , $assignment->repository_uri],
            [ 'Repository type' , $assignment->repository_type],
            [ 'Forge Site' , $assignment->forge_site],
            [ 'Forge server' , $assignment->forge_server],
            [ 'Groups' , join(', ', collect($assignment->groups)->pluck('name')->toArray()) ],
            [ 'Users' , join(', ', collect($assignment->users)->pluck('name')->toArray())],
            [ 'Teachers' , join(', ', collect($assignment->assignators)->pluck('name')->toArray())],
            [ 'Created' , $assignment->created_at],
            [ 'Updated' , $assignment->updated_at]
        ];

        $this->table($headers, $rows);
    }

    /**
     * Show assignment.
     *
     * @return array|mixed
     */
    protected function fetch_assignment()
    {
        $uri = str_replace('{assignment}', $this->assignment, config('forge-publish.show_assignment_uri'));
        $url = config('forge-publish.url') . $uri;
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
