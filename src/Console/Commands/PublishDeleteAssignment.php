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
 * Class PublishDeleteAssignment.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishDeleteAssignment extends Command
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
    protected $signature = 'publish:delete_assignment {assignment? : The assignment to remove}' ;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes an assignment';

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

        $this->delete_assignment();
    }

    /**
     * Delete assignment.
     *
     * @return array|mixed
     */
    protected function delete_assignment()
    {
        $uri = str_replace('{assignment}', $this->assignment, config('forge-publish.update_assignment_uri'));
        $url = config('forge-publish.url') . $uri;
        try {
            $response = $this->http->delete($url, [
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
    }

    /**
     * Abort command execution.
     */
    protected function abortCommandExecution()
    {
        $this->abortsIfEnvVarIsNotInstalled('ACACHA_FORGE_ACCESS_TOKEN');
    }
}
