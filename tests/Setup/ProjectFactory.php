<?php


namespace Tests\Setup;


use App\Project;
use App\Task;
use App\User;

/**
 * Class ProjectFactory
 * @package Tests\Setup
 * @method $this withTask(integer $count)
 */
class ProjectFactory
{
    protected $tasks_count = 0;
    protected $user = null;

    public function withTasks($count)
    {
        $this->tasks_count = $count;
        return $this;
    }

    public function ownedBy(?User $user)
    {
        $this->user = $user;
        return $this;
    }

    public function create()
    {
        $project = factory(Project::class)->create(
            [
                'owner_id' => $this->user ?: factory(User::class),

            ]
        );

        if ($this->tasks_count) {
            factory(Task::class, $this->tasks_count)->create([
                'project_id' => $project->id,
            ]);
        }

        return $project;
    }

}
