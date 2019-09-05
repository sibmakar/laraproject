<?php

namespace Tests\Unit;

use App\Project;
use App\Task;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    //use RefreshDatabase;

    public function testTaskBelongsToProject()
    {
        $task = factory(Task::class)->create();
        $this->assertInstanceOf(Project::class, $task->project);
    }

    public function testItHasPath()
    {
        $this->withoutExceptionHandling();
        $task = factory(Task::class)->create();

        $this->assertEquals('/projects/' . $task->project->id . '/tasks/' . $task->id, $task->path());
    }

    public function testItHasComplete()
    {
        $task = factory(Task::class)->create();

        $this->assertFalse($task->completed);

        $task->complete();

        $this->assertTrue($task->fresh()->completed);
    }

    public function testItHasIncomplete()
    {
        $task = factory(Task::class)->create(['completed' => true]);
//        $task->complete();
        $this->assertTrue($task->completed);

        $task->incomplete();
        $this->assertFalse($task->fresh()->completed);
    }
}
