<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> Binding
 *
 * <Function Name> Binding Model<br>
 * Create : 2018.07.26 TP Bryan<br>
 * Update : 2018.07.27 TP Bryan    Fixed code structure
 *          2018.08.20 TP Bryan    Fixed code structure
 *          2019.07.16 TP Mark     Applying Horizontal Expansion<br>
 *
 * <Overview> This model represents the Binding and its relationship with other models.
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class Binding extends Model
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // __construct                     (0.0) Reassign properties inherited from parent class
    // sourceDevice                    (1.0) Binding belongsTo Device relationship
    // targetDevice                    (2.0) Binding hasOne Device relationship
    // bindingList                     (3.0) Binding hasOne BindingList relationship
    // learningCommand                 (4.0) Binding hasMany IrLearning relationship

    use EloquentJoin;

    protected $table = 'M008_BINDING';           // The table associated with the model.
    protected $primaryKey = 'BINDING_ID';        // The primary key for the model.
    protected $casts = [                         // The attributes that should be cast to native types.
        'SOURCE_DEVICE_CONDITION' => 'array',
        'CUSTOM_CONDITION' => 'array'
    ];

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> Binding and Source Binding Device Relationship<br>
     * <Function> Binding belongsTo Device relationship<br>
     *
     * @return object $this->belongsTo('App\Device', 'SOURCE_DEVICE_ID', 'DEVICE_ID')
     */
    public function sourceDevice()
    {
        return $this->belongsTo('App\Models\Device', 'SOURCE_DEVICE_ID', 'DEVICE_ID');
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> Binding and Target Binding Device Relationship<br>
     * <Function> Binding hasOne Device relationship<br>
     *
     * @return object $this->hasOne('App\Device', 'DEVICE_ID', 'TARGET_DEVICE_ID')
     */
    public function targetDevice()
    {
        return $this->hasOne('App\Models\Device', 'DEVICE_ID', 'TARGET_DEVICE_ID');
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> Binding and Binding List Relationship<br>
     * <Function> Binding hasOne BindingList relationship<br>
     *
     * @return object $this->hasOne('App\BindingList', 'BINDING_LIST_ID', 'BINDING_LIST_ID')
     */
    public function bindingList()
    {
        return $this->hasOne('App\Models\BindingList', 'BINDING_LIST_ID', 'BINDING_LIST_ID');
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> Binding and IR Learning Relationship<br>
     * <Function> Binding hasMany IrLearning relationship<br>
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function learningCommand()
    {
        return $this->hasMany('App\Models\IrLearning', 'DEVICE_ID', 'TARGET_DEVICE_ID');
    }
}
