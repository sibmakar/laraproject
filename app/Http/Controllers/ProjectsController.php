<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRequest;
use App\Project;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->projects;

        return view('projects.index', compact('projects'));
    }


    public function create(){
        return view('projects.create');
    }


    public function store()
    {
       $project = auth()->user()->projects()->create($this->validateRequest());

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
