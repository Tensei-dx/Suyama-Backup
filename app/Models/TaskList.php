<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'M025_TASK_LIST';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'TASK_ID';
}
