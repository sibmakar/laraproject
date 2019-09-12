<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRequest;
use App\Project;
use Illuminate\Support\Str;

class ProjectsController extends Controller
{
    public function index()
    {
//        $projects = auth()->user()->projects;
        $projects = auth()->user()->accessibleProjects();

        return view('projects.index', compact('projects'));
    }


    public function create()
    {
        return view('projects.create');
    }


    /**
     * @return array|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        $project = auth()->user()->projects()->create($this->validateRequest());

        if($tasks = array_filter(request('tasks'), function($task){
            return Str::length($task['body']) > 2;
        })){
            $project->addTasks($tasks);
        }

        if (request()->wantsJson()) {
            return ['message' => $project->path()];
        }

        return redirect($project->path());
    }

    public function show(Project $project)
    {
        $this->authorize('update', $project);

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function destroy(Project $project)
    {
        $this->authorize('manage', $project);

        $project->delete();
        return redirect('/projects');
    }


    public function update(UpdateRequest $form)
    {

//        $form->save();
        return redirect($form->save()->path());
    }

    /**
     * @return mixed
     */
    public function validateRequest()
    {
        return request()->validate([
            'title' => 'sometimes|required',
            'description' => 'sometimes|required',
            'notes' => 'nullable'
        ]);
    }


}
