<?php


namespace App;


use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait RecordsActivity
{


    public $oldAttributes = [];


    /**
     *
     */
    public static function bootRecordsActivity()
    {


        $recordableEvents = self::recordableEvents();

        foreach ($recordableEvents as $event) {

            static::$event(function ($model) use ($event) {



                $model->recordActivity($model->activityDescription($event));

            });

            if($event === 'updated'){
                static::updating(function ($model) {
                    $model->oldAttributes = $model->getOriginal();
                });
            }
        }

    }


    /**
     * @param $description
     * @return string
     */
    public function activityDescription($description){
            return $description . "_" . Str::lower(class_basename($this));
    }

    /**
     * @return array|mixed
     */
    public static function recordableEvents()
    {
        if (isset(static::$recordableEvents)) {
            return static::$recordableEvents;
        }
        return [
            'created', 'updated'
        ];

    }


    /**
     * @param $description
     */
    public function recordActivity($description)
    {

        $this->activity()->create([
            'description' => $description,
            'changes' => $this->activityChanges(),
            'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project->id,
            'user_id' => ($this->project ?? $this)->owner->id,
        ]);

    }


    /**
     * @return array|null
     */
    public function activityChanges()
    {
        return $this->wasChanged() ? [
            'before' => Arr::except(array_diff($this->oldAttributes, $this->getAttributes()), 'updated_at'),
            'after' => Arr::except($this->getChanges(), 'updated_at')
        ] : null;
    }



}
