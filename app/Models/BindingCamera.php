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
 * Create : 2018.07.26 TP Harvey
 *
 * <Overview> This model represents the Binding and its relationship with other models.
 * @package Model
 * @author TP Uddin <t-harvey@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class BindingCamera extends Model
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

    protected $table = 'M020_BINDING_CAMERA';          // The table associated with the model.
    protected $primaryKey = 'BINDING_CAMERA_ID';       // The primary key for the model.
    protected $casts = [                               // The attributes that should be cast to native types.
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
}
