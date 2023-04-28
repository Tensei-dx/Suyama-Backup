<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> Appliances
 *
 * <Function Name> Appliances Model<br>
 * Create : 2019.06.21 TP Yani<br>
 * Update : <br>
 *
 * <Overview> This model represents the Appliances and its relationship with other models.
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class Appliances extends Model
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // __construct                      (1.0) Reassign properties inherited from parent class
    // device                           (2.0) Appliances hasMany IrLearning relationship
    // getSearchableColumns             (3.0) Get columns that will be used for searching

    use EloquentJoin;

    protected $table = 'M015_APPLIANCES';          // The table associated with the model.
    protected $primaryKey = 'APPLIANCE_ID';        // The primary key for the model.


    /**
     * The columns that will be used for searching
     *
     * @var array
     */
    protected $searchableColumns = [
        'APPLIANCE_NAME', 'APPLIANCE_TYPE', 'BRAND_NAME'
    ];

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> Appliances and IR Learning Command Relationship<br>
     * <Function> Appliances hasMany IrLearning relationship<br>
     *
     * @return object $this->hasMany('App\IrLearning', 'APPLIANCE_ID');
     */
    public function IrLearning()
    {
        return $this->hasMany('App\Models\IrLearning', 'APPLIANCE_ID');
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> Get searchable columns<br>
     * <Function> Get columns that will be used for searching<br>
     *
     * @return array|string $this->searchableColumns;
     */
    public function getSearchableColumns()
    {
        return $this->searchableColumns;
    }
}
