<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> BacnetDeviceList
 *
 * <Function Name> Bacnet Device List Model<br>
 * Create : 2020.02.19  TP Uddin<br>
 * Update : <br>
 *
 * <Overview> This model represents the Bacnet Device List and its relationship with other models.
 */
class BacnetDeviceList extends Model
{
    /*******************************************************************/
    /* Processing Hierarchy                                            */
    /*******************************************************************/
    // __construct                      (0.0) Reassign properties inherited from parent class
    // bacnetDevice                     (1.0) BacnetDeviceList belongsTo BacnetDevice relationship
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
        'PRED_DEVICE_ID', 'PRED_DEVICE_NAME'
    ];

    protected $table = 'M018_BACNET_DEVICE_LIST';       // The table associated with the model.
    protected $primaryKey = 'PRED_DEVICE_ID';           // The primary key for the model.


    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Bacnet Device List and Bacnet Device Relationship<br>
     * <Function> BacnetDeviceList belongsTo BacnetDevice relationship<br>
     */
    public function bacnetDevice()
    {
        $this->belongsTo('App\Models\BacnetDevice', 'BACNETDEVICE_ID', 'BACNETDEVICE_ID');
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> Get Searchable Columns<br>
     * <Function> Get columns that will be used for searching<br>
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getSearchableColumns()
    {
        return $this->searchableColumns;
    }
}
