<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> BindingList
 *
 * <Function Name> Binding List Model<br>
 * Create : 2018.07.26 TP Bryan<br>
 * Update : 2018.07.27 TP Bryan    Fixed code structure<br>
 *
 * <Overview> This model represents the Binding List and its relationship with other models.
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class BindingList extends Model
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // __construct                      (0.0) Reassign properties inherited from parent class
    // bindings                         (1.0) BindingList hasMany Bindings relationship
    // sourceDevices                    (2.0) BindingList hasManyThrough Source Device and Binding relationship
    // targetDevices                    (3.0) BindingList hasManyThrough Target Device and Binding relationship

    use EloquentJoin;

    protected $table = 'M009_BINDING_LIST';      // The table associated with the model.
    protected $primaryKey = 'BINDING_LIST_ID';   // The primary key for the model.


    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Binding List and Device Binding Relationship<br>
     * <Function> BindingList hasMany Binding relationship<br>
     *
     * @return object $this->hasMany('App\Binding', 'BINDING_LIST_ID', 'BINDING_LIST_ID')
     */
    public function bindings()
    {
        return $this->hasMany('App\Models\Binding', 'BINDING_LIST_ID', 'BINDING_LIST_ID');
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> Binding List and Source Device Relationship<br>
     * <Function> BindingList hasManyThrough Source Device and Binding relationship<br>
     *
     * @return object $source
     */
    public function sourceDevices()
    {
        $source = $this->hasManyThrough(
            'App\Models\Device',    // Has Many
            'App\Models\Binding',   // Through
            'BINDING_LIST_ID',      // Foreign key on Binding
            'DEVICE_ID',            // Foreign key on Device
            'BINDING_LIST_ID',      // Primary key on (this) Device
            'SOURCE_DEVICE_ID'      // Primary key on Binding
        );
        return $source;
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> Binding List and Target Device Relationship<br>
     * <Function> BindingList hasManyThrough Target Device and Binding relationship<br>
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function targetDevices()
    {
        $target = $this->hasManyThrough(
            'App\Models\Device',    // Has Many
            'App\Models\Binding',   // Through
            'BINDING_LIST_ID',      // Foreign key on Binding
            'DEVICE_ID',            // Foreign key on Device
            'BINDING_LIST_ID',      // Primary key on (this) Device
            'TARGET_DEVICE_ID'      // Primary key on Binding
        );
        return $target;
    }
}
