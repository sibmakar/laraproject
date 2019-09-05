<?php


namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait RecordsActivity
{


    public $oldAttributes = [];


    public static function bootRecordsActivity()
    {
        static::updating(function ($model) {
            $model->oldAttributes = $model->getOriginal();
        });

        if (isset(static::$recordableEvents)) {
            $recordableEvents = static::$recordableEvents;
        } else {
            $recordableEvents = [
                'created', 'updated', 'deleted'
            ];
        }
        foreach ($recordableEvents as $event) {
            static::$event(function ($model) use ($event) {
                if (class_basename($model) !== 'Project') {
                    $model->recordActivity($event . "_" . Str::lower(class_basename($model)));
                } else {
                    $model->recordActivity($event);
                }
            });
        }

    }


    public function recordActivity($description)
    {

        $this->activity()->create([
            'description' => $description,
            'changes' => $this->activityChanges(),
            'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project->id,
        ]);

    }


    public function activityChanges()
    {
        return $this->wasChanged() ? [
            'before' => Arr::except(array_diff($this->oldAttributes, $this->getAttributes()), 'updated_at'),
            'after' => Arr::except($this->getChanges(), 'updated_at')
        ] : null;
    }

}
