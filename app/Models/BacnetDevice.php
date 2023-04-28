<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> BacnetDevice
 *
 * <Function Name> Bacnet Device Model<br>
 * Create : 2020.01.27  TP Uddin<br>
 * Update : <br>
 *
 * <Overview> This model represents the Bacnet Devices and its relationship with other models.
 */
class BacnetDevice extends Model
{
    /****************************************************************/
    /* Processing Hierarchy                                         */
    /****************************************************************/
    // __construct                     (0.0) Reassign properties inherited from parent class
    // floor                           (1.0) BacnetDevice belongsTo Floor relationship
    // room                            (2.0) BacnetDevice belongsTo Room relationship
    // gateway                         (3.0) BacnetDevice belongsTo Gateway relationship
    // manufacturer                    (4.0) BacnetDevice belongsTo Manufacturer relationship
    // data                            (5.0) BacnetDevice hasMany BacnetData relationship
    // predefinedDetails               (6.0) BacnetDevice hasMany BacnetDeviceList relationship
    // getSearchableColumns            (7.0) Get columns that will be used for searching

    use EloquentJoin;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'CREATED_AT';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'UPDATED_AT';

    /**
     * The columns that will be used for searching.
     *
     * @var array
     */
    protected $searchableColumns = [
        'BACNETDEVICE_ID', 'DEVICE_ID', 'DEVICE_SERIAL_NO',
    ];

    protected $table = 'M017_BACNET';            // The table associated with the model.
    protected $primaryKey = 'BACNETDEVICE_ID';  // The primary key for the model.
    protected $fillable = [
        'ROOM_ID'
    ];

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Bacnet Device and Floor Relationship<br>
     * <Function> BacnetDevice belongsTo Floor relationship<br>
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function floor()
    {
        return $this->belongsTo('App\Models\Floor', 'FLOOR_ID', 'FLOOR_ID');
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> Bacnet Device and Room Relationship<br>
     * <Function> BacnetDevice belongsTo Room relationship<br>
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function room()
    {
        return $this->belongsTo('App\Models\Room', 'ROOM_ID', 'ROOM_ID');
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> Bacnet Device and Gateway Relationship<br>
     * <Function> BacnetDevice belongsTo Gateway relationship<br>
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function gateway()
    {
        return $this->belongsTo('App\Models\Gateway', 'GATEWAY_ID', 'GATEWAY_ID');
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> Bacnet Device and Manufacturer Relationship<br>
     * <Function> BacnetDevice belongsTo Manufacturer relationship<br>
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function manufacturer()
    {
        return $this->belongsTo('App\Models\Manufacturer', 'MANUFACTURER_ID', 'MANUFACTURER_ID');
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> Bacnet Device and Bacnet Data Relationship<br>
     * <Function> Bacnet Device hasMany BacnetData relationship<br>
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function data()
    {
        return $this->hasMany('App\Models\BacnetData', 'BACNETDEVICE_ID');
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> Bacnet Device and Bacnet Device List Relationship<br>
     * <Function> BacnetDevice hasMany BacnetDeviceList relationship<br>
     */
    public function predefinedDetails()
    {
        return $this->hasMany('App\Models\BacnetDeviceList', 'PRED_DEVICE_NAME', 'DEVICE_TYPE');
    }

    /**
     * <Layer number> (7.0)
     *
     * <Processing name> Get Searchable Column<br>
     * <Function> Get columns that will be used for searching<br>
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getSearchableColumns()
    {
        return $this->searchableColumns;
    }
}
