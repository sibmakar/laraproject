<?php

namespace Tests\Feature;

use App\Task;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TriggerActivityTest extends TestCase
{
    //use RefreshDatabase;

    /**
     * @test
     */
    public function creating_a_project()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $this->assertCount(1, $project->activity);


        tap($project->activity->last(), function ($activity){
            $this->assertEquals('created_project', $activity->description);

            $this->assertNull($activity->changes);
        });

    }

    /**
     * @test
     */
    public function updating_a_project()
    {
        $this->withoutExceptionHandling();


        $project = ProjectFactory::create();

        $expected = [
            'before' => ['title' => $project->title],
            'after' => ['title' => 'New Title for Test']
        ];

        $project->update(['title' => 'New Title for Test']);
        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function ($activity) use ($expected){
            $this->assertEquals('updated_project', $activity->description);

            $this->assertEquals($expected, $activity->changes);
        });


    }


    /**
     * @test
     */
    public function updating_a_project_after_new_task_created()
    {
        $this->withoutExceptionHandling();


        $project = ProjectFactory::create();

        $expected = [
            'before' => ['title' => $project->title],
            'after' => ['title' => 'New Title for Test']
        ];
        $project->addTask('Some Body');
        $project->update(['title' => 'New Title for Test']);
        $this->assertCount(3, $project->activity);

        tap($project->activity->last(), function ($activity) use ($expected){
            $this->assertEquals('updated_project', $activity->description);

            $this->assertEquals($expected, $activity->changes);
        });


    }

    /**
     * @test
     */
    public function creating_a_new_task()
    {

        $this->withoutExceptionHandling();

        $project = ProjectFactory::create();
        $project->addTask('Some Body');
        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function ($activity){

//            dd($activity->toArray());

            $this->assertEquals('created_task', $activity->description);
            $this->assertEquals('Some Body', $activity->subject->body);
            $this->assertInstanceOf(Task::class, $activity->subject);

        });

    }

    /**
     * @test
     */
    public function ucompleting_a_new_task()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::withTasks(1)->create();


        $project->tasks[0]->complete();

        $project = $project->fresh();

        $this->assertCount(3, $project->activity);

        tap($project->activity->last(), function ($activity){

//            dd($activity->toArray());

            $this->assertEquals('completed_task', $activity->description);
            //$this->assertEquals('Some Body', $activity->subject->body);
            $this->assertInstanceOf(Task::class, $activity->subject);

        });

    }

      /**
     * @test
     */
    public function incompleting_a_new_task()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)->patch($project->tasks[0]->path(), [
            'body' => 'Some Body Tasks',
            'completed' => true,
        ]);

        $project = $project->fresh();

        $project->tasks[0]->incomplete();

        $project = $project->fresh();


        $this->assertCount(4, $project->activity);

        tap($project->activity->last(), function ($activity){

//            dd($activity->toArray());

            $this->assertEquals('incompleted_task', $activity->description);
            //$this->assertEquals('Some Body', $activity->subject->body);
            $this->assertInstanceOf(Task::class, $activity->subject);

        });

    }


    /**
     * @test
     */
    public function deleting_a_task()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::withTasks(1)->create();

        $project->tasks->first()->delete();

        $this->assertCount(3, $project->activity);
        $this->assertEquals('deleted_task', $project->activity->last()->description);
    }




}
