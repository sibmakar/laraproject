<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Project extends Model
{

    use RecordsActivity;

    protected $guarded = [];


    public function path()
    {
        return '/projects/' . $this->id;
    }

    public function addTasks(array $tasks)
    {
        return $this->tasks()->createMany($tasks);
    }


    public function addTask($body)
    {
        return $this->tasks()->create(compact('body'));

    }

    public function invite(User $user)
    {
        return $this->members()->attach($user);
    }


    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members');
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
    }


}
