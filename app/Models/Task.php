<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\CronTranslator\CronTranslator;

class Task extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'M032_TASKS';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'TASK_ID';

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'TASK_ID',
        'COMMAND',
        'CRON_SCHEDULE',
        'ACTIVE_FLAG'
    ];

    /**
     * The attributes that should be cast.
     * 
     * @var array
     */
    protected $casts = [
        'ACTIVE_FLAG' => 'boolean'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'CRON_DESCRIPTION'
    ];

    /**
     * Get the human-readable description of the cron schedule.
     *
     * @return string
     */
    public function getCronDescriptionAttribute()
    {
        return CronTranslator::translate($this->attributes['CRON_SCHEDULE']);
    }

    /**
     * Scope the query to only include active tasks.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query, bool $value = true)
    {
        return $query->where('ACTIVE_FLAG', $value);
    }
}
