<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> Floor
 *
 * <Function Name> Floor Model<br>
 * Create : 2018.06.25 TP Bryan<br>
 * Update : 2018.06.29 TP Bryan    Added gateways()
 *          2018.07.02 TP Bryan    Added "Processing Hierarchy"
 *          2018.07.10 TP Bryan    Defined table/primaryKey property through constructor
 *          2018.08.08 TP Bryan    Added 4.0
 *          2018.08.20 TP Bryan    Fixed code structure
 *          2019.06.11 TP Harvey   Applying Coding Standard
 *          2020.05.13 TP Uddin    Implement coding standard for PHP7<br>
 *
 * <Overview> This model represents the Floor and its relationship with other models.
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class Floor extends Model
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // __construct                      (0.0) Reassign properties inherited from parent class
    // rooms                            (1.0) Floor hasMany Room relationship
    // gateways                         (2.0) Floor hasMany Gateways relationship
    // devices                          (3.0) Floor hasMany Devices relationship
    // users                            (4.0) Floor belongsToMany User relationship
    // getSearchableColumns             (5.0) Get columns that will be used for searching

    use EloquentJoin;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'M003_FLOOR';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'FLOOR_ID';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'FLOOR_MAP_DATA' => 'array'
    ];

    /**
     * The columns that will be used for searching.
     *
     * @var array
     */
    protected $searchableColumns = [
        'FLOOR_NAME'
    ];

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> rooms <br>
     * <Function> The rooms associated to the floor <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rooms()
    {
        return $this->hasMany('App\Models\Room', 'FLOOR_ID', 'FLOOR_ID');
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> gateways <br>
     * <Function> The gateways associated to the floor <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gateways()
    {
        return $this->hasMany('App\Models\Gateway', 'FLOOR_ID', 'FLOOR_ID');
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> devices <br>
     * <Function> The devices associated to the floor <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function devices()
    {
        return $this->hasMany('App\Models\Device', 'FLOOR_ID', 'FLOOR_ID');
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> users <br>
     * <Function> The users who have authorization to the floor <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(
            'App\Models\User',
            'M002_AUTH_LOCATION',
            'FLOOR_ID',
            'USER_ID'
        );
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> getSearchableColumn <br>
     * <Function> The attributes that will be used for searching <br>
     *
     * @return array
     */
    public function getSearchableColumns()
    {
        return $this->searchableColumns;
    }
}
