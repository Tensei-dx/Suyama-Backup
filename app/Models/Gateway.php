<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> Gateway
 *
 * <Function Name> Gateway Model<br>
 * Create : 2018.06.20 TP Bryan<br>
 * Update : 2018.06.25 TP Bryan    Fixed comments
 *          2018.07.02 TP Bryan    Added "Processing Hierarchy"
 *          2018.07.10 TP Bryan    Defined table/primaryKey property through constructor
 *          2018.07.10 TP Bryan    Added
 *          2018.08.20 TP Bryan    Fixed code structure
 *          2019.06.11 TP Harvey   Applying Coding Standard<br>
 *
 * <Overview> This model represents the Gateway and its relationship with other models.
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class Gateway extends Model
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // __construct                      (0.0) Reassign properties inherited from parent class
    // floor                            (1.0) Gateway belongsTo Floor relationship
    // room                             (2.0) Gateway belongsTo Room relationship
    // devices                          (3.0) Gateway hasMany Devices relationship
    // getSearchableColumns             (4.0) Get columns that will be used for searching

    use EloquentJoin;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'M005_GATEWAY';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'GATEWAY_ID';

    /**
     * The columns that will be used for searching.
     *
     * @var array
     */
    protected $searchableColumns = [
        'GATEWAY_SERIAL_NO', 'GATEWAY_IP', 'GATEWAY_NAME'
    ];

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> floor <br>
     * <Function> The floor associated to the gateway <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function floor()
    {
        return $this->belongsTo('App\Models\Floor', 'FLOOR_ID', 'FLOOR_ID');
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> room <br>
     * <Function> The room associated to the gateway <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room()
    {
        return $this->belongsTo('App\Models\Room', 'ROOM_ID', 'ROOM_ID');
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> devices <br>
     * <Function> The devices associated to the gateway <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function devices()
    {
        return $this->hasMany('App\Models\Device', 'GATEWAY_ID', 'GATEWAY_ID');
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> getSearchableColumns <br>
     * <Function> Get the attributes that will be used to searching <br>
     *
     * @return array
     */
    public function getSearchableColumns()
    {
        return $this->searchableColumns;
    }
}
