<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> BacnetData
 *
 * <Function Name> Bacnet Device Data Model<br>
 * Create : 2020.02.03 TP Uddin<br>
 * Update : <br>
 *
 * <Overview> This model represents the Bacnet Device Data and its relationship with other models.
 */
class BacnetData extends Model
{
    /*******************************************************************/
    /* Processing Hierarchy                                            */
    /*******************************************************************/
    // __construct                      (0.0) Reassign properties inherited from parent class
    // bacnetDevice                     (1.0) BacnetData belongsTo BacnetDevice relationship
    // getSearchableColumns             (2.0) Get columns that will be used for searching

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
     * The columns that will be used for searching
     *
     * @var array
     */
    protected $searchableColumns = [
        'BACNETDEVICE_ID', 'BACNET_DATA_ID',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'GATEWAY_ID', 'MANUFACTURER_ID', 'BACNETDEVICE_ID', 'DEVICE_ID', 'OBJECT_TYPE', 'OBJECT_ID', 'OBJECT_NAME', 'OBJECT_VALUE', 'DESCRIPTION', 'REG_FLAG', 'ONLINE_FLAG'
    ];

    protected $table = 'T008_BACNET_DATA';          // The table associated with the model.
    protected $primaryKey = 'BACNET_DATA_ID';       // The primary key for the model.

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Bacnet Device Data and Bacnet Device Relationship<br>
     * <Function> BacnetData belongsTo BacnetDevice relationship<br>
     */
    public function bacnetDevice()
    {
        $this->belongsTo('App\Models\BacnetDevice', 'BACNETDEVICE_ID', 'BACNETDEVICE_ID');
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> Get searchable columns<br>
     * <Function> Get columns that will be used for searching<br>
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getSearchableColumns()
    {
        return $this->searchableColumns;
    }
}
