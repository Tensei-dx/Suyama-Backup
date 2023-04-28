<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class AccessPerson extends Model
{
    use Uuids;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'M033_ACCESS_PEOPLE';

    /**
     * The primary key associated with the table.
     * 
     * @var string
     */
    protected $primaryKey = 'ACCESS_PERSON_ID';

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'ACCESS_PERSON_ID',
        'USER_ID',
        'ACCESS_TYPE',
        'ACCESS_STARTS_AT',
        'ACCESS_ENDS_AT'
    ];

    /**
     * The user associated to the access person.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'USER_ID', 'USER_ID');
    }
}
