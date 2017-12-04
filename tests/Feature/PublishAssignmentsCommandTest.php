<?php

namespace Tests\Feature;

use Acacha\ForgePublish\Exceptions\EnvironmentVariableNotFoundException;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Mockery;
use Tests\TestCase;

/**
 * Class PublishAssignmentCommandTest.
 */
class PublishAssignmentsCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Fake assignments.
     *
     * @return array
     */
    protected function fake_assignments()
    {
        $assignment1 =  [
            'id' => 1,
            'name' => 'Name1',
            'repository_uri' => 'acacha/test1',
            'repository_type' => 'Github',
            'forge_site' => 123,
            'forge_server' => 123123,
            'created_at' => '1 day ago',
            'updated_at' => '1 day ago',
        ];

        $assignment2 = [
            'id' => 1,
            'name' => 'Name2',
            'repository_uri' => 'acacha/test2',
            'repository_type' => 'Github',
            'forge_site' => 1233,
            'forge_server' => 1232123,
            'created_at' => '1 day ago',
            'updated_at' => '1 day ago',
        ];

        $assignment3 = [
            'id' => 1,
            'name' => 'Name3',
            'repository_uri' => 'acacha/test2',
            'repository_type' => 'Github',
            'forge_site' => 1233,
            'forge_server' => 12326123,
            'created_at' => '1 day ago',
            'updated_at' => '1 day ago',
        ];

        return [
            $assignment1,
            $assignment2,
            $assignment3
        ];
    }

    /**
     * Command aborts.
     *
     * @test
     */
    public function command_aborts()
    {
        $client = new Client();
        $command = Mockery::mock('Acacha\ForgePublish\Commands\PublishAssignments[fp_env]', [$client])
            ->shouldAllowMockingProtectedMethods();

        $command->shouldReceive('fp_env')
            ->once()
            ->with('ACACHA_FORGE_ACCESS_TOKEN')
            ->andReturn(null);

        $this->app['Illuminate\Contracts\Console\Kernel']->registerCommand($command);

        $this->expectException(EnvironmentVariableNotFoundException::class);

        $this->artisan('publish:assignments');
    }

    /**
     * List assignments
     *
     * @test
     */
    public function list_assignments()
    {
        $assignments = $this->fake_assignments();

        $client = new Client();
        $command = Mockery::mock('Acacha\ForgePublish\Commands\PublishAssignments[fetchAssignments]', [$client]);

        $command->shouldReceive('fetchAssignments')
            ->once()
            ->with()
            ->andReturn($assignments);

        $this->app['Illuminate\Contracts\Console\Kernel']->registerCommand($command);

        $this->artisan('publish:assignments');

        $resultAsText = Artisan::output();


        foreach ($assignments as $assignment) {
            $this->assertContains((String) $assignment['id'], $resultAsText);
            $this->assertContains((String) $assignment['name'], $resultAsText);
            $this->assertContains((String) $assignment['repository_uri'], $resultAsText);
            $this->assertContains((String) $assignment['repository_type'], $resultAsText);
            $this->assertContains((String) $assignment['forge_site'], $resultAsText);
            $this->assertContains((String) $assignment['forge_server'], $resultAsText);
            $this->assertContains((String) $assignment['created_at'], $resultAsText);
            $this->assertContains((String) $assignment['updated_at'], $resultAsText);
        }
    }

    /**
     * Create new event with_wizard.
     *
     * @TODO
     */
    public function create_new_task_with_wizard()
    {
        $user = factory(User::class)->create();

        $command = Mockery::mock('App\Console\Commands\CreateTaskCommand[ask,choice]');

        $command->shouldReceive('ask')
            ->once()
            ->with('Event name?')
            ->andReturn('Comprar llet');

        $command->shouldReceive('ask')
            ->once()
            ->with('Description?')
            ->andReturn('Una pasada de tasca');

        $command->shouldReceive('choice')
            ->once()
            ->with('User?', [0 => $user->name])
            ->andReturn($user->name);

        $this->app['Illuminate\Contracts\Console\Kernel']->registerCommand($command);

        $this->artisan('task:create');

        $this->assertDatabaseHas('tasks', [
            'name'        => 'Comprar llet',
            'user_id'     => $user->id,
            'description' => 'Una pasada de tasca',
        ]);

        $resultAsText = Artisan::output();
        $this->assertContains('Task has been added to database succesfully', $resultAsText);
    }
}
