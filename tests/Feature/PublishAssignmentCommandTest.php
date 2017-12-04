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
class PublishAssignmentCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Command aborts.
     *
     * @test
     */
    public function command_aborts()
    {
        $client = new Client();
        $command = Mockery::mock('Acacha\ForgePublish\Commands\PublishAssignment[fp_env]', [$client])
            ->shouldAllowMockingProtectedMethods();

        $command->shouldReceive('fp_env')
            ->once()
            ->with('ACACHA_FORGE_ACCESS_TOKEN')
            ->andReturn(null);

        $this->app['Illuminate\Contracts\Console\Kernel']->registerCommand($command);

        $this->expectException(EnvironmentVariableNotFoundException::class);

        $this->artisan('publish:assignment');
    }

    /**
     * Create assignment
     *
     * @test
     */
    public function create_assignment()
    {
        $client = new Client();
        $command = Mockery::mock('Acacha\ForgePublish\Commands\PublishAssignment[createAssignment]', [$client])
            ->shouldAllowMockingProtectedMethods();

        $command->shouldReceive('createAssignment')
            ->once()
            ->with()
            ->andReturn();

        $this->app['Illuminate\Contracts\Console\Kernel']->registerCommand($command);

        $this->artisan('publish:assignment', [
            'name' => 'New assignment'
        ]);

        $resultAsText = Artisan::output();

        $this->assertContains('Assignment created ok!', $resultAsText);

    }

    /**
     * Create new task with_wizard.
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
