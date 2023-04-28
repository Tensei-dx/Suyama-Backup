<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParamSettings extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'T019_PARAM_SETTINGS';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'PARAM_ID';

    /**
     * The attributes that are mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'AC_AUTO_START',
        'AC_START_OFFSET',
        'AC_MODE',
        'RL_NUM_PIN',
        'MAIL_THANKYOU_CONTENT',
        'MAIL_REMIND_OFFSET',
        'WIFI_NAME',
        'WIFI_PASSWORD'
    ];
}
