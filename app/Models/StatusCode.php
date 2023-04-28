<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusCode extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'M022_STATUS_CODE';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'STATUS_ID';
}
