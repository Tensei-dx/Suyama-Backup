<?php

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Model;

class Management extends Model
{

    use EloquentJoin;

    protected $table = 'M004_ROOM';           // The table associated with the model.
    protected $primaryKey = 'ROOM_ID';        // The primary key for the model.
    protected $casts = [                      // The attributes that should be cast to native types.
        'ROOM_MAP_DATA' => 'array'
    ];



    /**
     * The columns that will be used for searching<br>
     *
     * @var array
     */
    protected $searchableColumns = [
        'ROOM_NAME'
    ];

    /**
     * This will not input UPDATED_AT/CREATED_AT on our Database
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Room and Floor Relationship<br>
     * <Function> Room belongsTo Floor relationship<br>
     *
     * @return mixed $this->belongsTo('App\Floor', 'FLOOR_ID', 'FLOOR_ID')
     */
    public function floor()
    {
        return $this->belongsTo('App\Models\Floor', 'FLOOR_ID', 'FLOOR_ID');
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> Room and Gateway Relationship<br>
     * <Function> Room hasMany Gateways relationship<br>
     *
     * @return mixed $this->hasMany('App\Gateway', 'ROOM_ID')
     */
    public function gateways()
    {
        return $this->hasMany('App\Models\Gateway', 'ROOM_ID');
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> Room and Wulian Gateways Relationship<br>
     * <Function> Room hasMany Registered Wulian Gateways relationship<br>
     *
     * @return mixed $regGateways
     */
    public function registeredGateways()
    {
        $regGateways = $this->hasMany('App\Models\Gateway', 'ROOM_ID')
            ->where([['REG_FLAG', 1], ['MANUFACTURER_ID', 1]]);
        return $regGateways;
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> Room and Devices Relationship<br>
     * <Function> Room hasMany Devices relationship<br>
     *
     * @return mixed $this->hasMany('App\Device', 'ROOM_ID')
     */
    public function devices()
    {
        return $this->hasMany('App\Models\Device', 'ROOM_ID');
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> Room and Registered Devices Relationship<br>
     * <Function> Room hasMany Registered Devices relationship<br>
     *
     * @return mixed $this->hasMany('App\Device', 'ROOM_ID')->where('REG_FLAG',1)
     */
    public function regDevices()
    {
        return $this->hasMany('App\Models\Device', 'ROOM_ID')->where('REG_FLAG', 1);
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> Get Searchable Columns<br>
     * <Function> Get columns that will be used for searching<br>
     *
     * @return array|string[] $this->searchableColumns
     */
    public function getSearchableColumns()
    {
        return $this->searchableColumns;
    }

    /**
     * <Layer number> (7.0)
     *
     * <Processing name> Room and Online Devices Relationship<br>
     * <Function> Room hasMany Online Devices relationship<br>
     *
     * @return mixed $this->hasMany('App\Device', 'ROOM_ID')->where('ONLINE_FLAG',1)
     */
    public function onDevices()
    {
        return $this->hasMany('App\Models\Device', 'ROOM_ID')->where('ONLINE_FLAG', 1);
    }

    /**
     * <Layer number> (9.0)
     *
     * <Processing name> Room and Notification Relationship<br>
     * <Function> Room hasMany Notification relationship<br>
     *
     * @return mixed $this->hasMany('App\Notification','ROOM_ID', 'ROOM_ID')
     */
    public function notification()
    {
        return $this->hasMany('App\Models\Notification', 'ROOM_ID', 'ROOM_ID');
    }
}
