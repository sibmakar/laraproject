<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;

class ProjectTasksController extends Controller
{
    public function store(Project $project)
    {
        $this->authorize('update', $project);

        request()->validate([
            'body' => 'required|string|min:3'
        ]);

        $project->addTask(request('body'));

        return redirect($project->path());
    }

    public function update(Project $project, Task $task)
    {
        $this->authorize('update', $task->project);

        $attributes = request()->validate([
            'body' => 'required|string|min:3'
        ]);

        $task->update($attributes);

        request('completed') ? $task->complete() : $task->incomplete();



        return redirect($project->path());
    }
}
