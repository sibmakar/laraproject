<?php

namespace Tests\Feature;

use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTasksTest extends TestCase
{

    use WithFaker;
    //use RefreshDatabase;





    /**
     * @test
     */
    public function only_the_owner_of_a_project_may_add_task()
    {

        $this->signIn();
        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test Tasks'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Test Tasks']);
    }

    /**
     * @test
     */
    public function guests_cannot_create_a_task()
    {

        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test'])->assertRedirect('/login');

    }



    /**
     * @test
     */
    public function only_the_owner_of_a_project_may_update_task()
    {

        $this->signIn();

        $project = ProjectFactory::withTasks(3)
            ->create();

        $this->patch($project->tasks->first()->path(), ['body' => 'Changed Test Task'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', [
            'id' => $project->tasks->first()->id,
            'body' => 'Changed Test Task'
        ]);

    }




    /**
     * @test
     */
    public function a_project_can_have_tasks()
    {

        $project = ProjectFactory::create();

        $body = $this->faker()->sentence;
        $this->actingAs($project->owner)->post($project->path() . '/tasks', ['body' => $body]);

        $this->get($project->path())->assertSee($body);

    }

    /**
     * @test
     */
    public function a_task_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::withTasks(1)
            ->create();

        $this->actingAs($project->owner)->patch($project->tasks->get(0)->path(), [
            'body' => 'New Body',
        ]);



        $this->assertDatabaseHas('tasks', [
            'id' => $project->tasks[0]->id,
            'body' => 'New Body',
        ]);
    }

    /**
     * @test
     */
    public function a_task_can_be_completed()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::withTasks(1)
            ->create();

        $this->actingAs($project->owner)->patch($project->tasks->get(0)->path(), [
            'body' => 'New Body',
            'completed' => true,
        ]);



        $this->assertDatabaseHas('tasks', [
            'id' => $project->tasks[0]->id,
            'body' => 'New Body',
            'completed' => true,
        ]);
    }

    /**
     * @test
     */
    public function a_task_can_be_mark_incomplete()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::withTasks(1)
            ->create();

        $this->actingAs($project->owner)->patch($project->tasks->get(0)->path(), [
            'body' => 'New Body',
            'completed' => true,
        ]);

        $this->patch($project->tasks->get(0)->path(), [
            'body' => 'New Body',
            'completed' => false,
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $project->tasks[0]->id,
            'body' => 'New Body',
            'completed' => false,
        ]);
    }


    /**
     * @test
     */

    public function a_task_required_body()
    {

        $project = ProjectFactory::create();
        $attributes = factory('App\Task')->raw(['body' => '', 'project_id' => $project->id]);

        $this->actingAs($project->owner)->post($project->path() . '/tasks', $attributes)->assertSessionHasErrors('body');
    }
}
