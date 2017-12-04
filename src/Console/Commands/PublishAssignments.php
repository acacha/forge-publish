<?php

namespace Acacha\ForgePublish\Commands;

use Acacha\ForgePublish\Commands\Traits\AborstIfEnvVariableIsnotInstalled;
use Acacha\ForgePublish\Commands\Traits\ItFetchesAssignments;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class PublishAssignments.
 *
 * @package Acacha\ForgePublish\Commands
 */
class PublishAssignments extends Command
{

    use ItFetchesAssignments, AborstIfEnvVariableIsnotInstalled;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:assignments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List current teacher assignments';

    /**
     * Assignments.
     *
     * @var
     */
    protected $assignments;

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
        $this->assignments = $this->fetchAssignments();

        $headers = ['Id', 'Name','Repository','Repo Type','Forge site','Forge Server','Created','Updated'];

        $rows = [];
        foreach ($this->assignments as $assignment) {
            $rows[] = [
                $assignment->id,
                $assignment->name,
                $assignment->repository_uri,
                $assignment->repository_type,
                $assignment->forge_site,
                $assignment->forge_server,
                $assignment->created_at,
                $assignment->updated_at
            ];
        }

        $this->table($headers, $rows);
    }

    /**
     * Abort command execution.
     */
    protected function abortCommandExecution()
    {
        $this->abortsIfEnvVarIsNotInstalled('ACACHA_FORGE_ACCESS_TOKEN');
    }
}
