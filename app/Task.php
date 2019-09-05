<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Task extends Model
{

    use RecordsActivity;

    protected $guarded = [];

    protected $touches = ['project'];

    protected $casts = [
        'completed' => 'boolean',
    ];

    public static $recordableEvents = [
        'created', 'deleted'
    ];

    public function path()
    {
        return $this->project->path() . '/tasks/' . $this->id;
    }

    public function complete()
    {
        $this->update(['completed' => true,]);
        $this->recordActivity('completed_task');
    }

    public function incomplete()
    {
        // if ($this->completed) {
        $this->update(['completed' => false,]);
        $this->recordActivity('incompleted_task');
        // }
    }


    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }



    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

}
