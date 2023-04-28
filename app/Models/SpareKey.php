<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpareKey extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'T018_SPARE_KEYS';

    /**
     * The primary key associated with the table.
     * 
     * @var string
     */
    protected $primaryKey = 'SPARE_KEY_ID';

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'ROOM_ID',
        'REMOTE_LOCK_DEVICE_ID',
        'REMOTE_LOCK_USER_ID',
        'PIN_CODE',
        'STARTS_AT',
        'ENDS_AT'
    ];

    /**
     * The name of the column for created at timestamp.
     * 
     * @var string
     */
    const CREATED_AT = 'CREATED_AT';

    /**
     * The name of the column for updated at timestamp.
     * 
     * @var string
     */
    const UPDATED_AT = 'UPDATED_AT';

    /**
     * The room where the spare key belongs to.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room()
    {
        return $this->belongsTo('App\Models\Room', 'ROOM_ID', 'ROOM_ID');
    }

    /**
     * The device where the spare key belongs to.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function device()
    {
        return $this->belongsTo('App\Models\Device', 'REMOTE_LOCK_DEVICE_ID', 'DEVICE_ID');
    }
}
