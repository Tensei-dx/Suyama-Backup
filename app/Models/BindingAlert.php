<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> BindingAlert
 *
 * <Function Name> BindingAlert Model<br>
 * Create : 2020.08.24 TP Yani<br>
 * Update :
 *
 * <Overview> This model represents the Binding and its relationship with other models.
 * @package Model
 * @author TP Yani <l-yani-tp@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class BindingAlert extends Model
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // __construct                     (0.0) Reassign properties inherited from parent class
    // sourceDevice                    (1.0) Binding belongsTo Device relationship

    use EloquentJoin;

    protected $table = 'M019_BINDING_ALERT';         // The table associated with the model.
    protected $primaryKey = 'BINDING_ALERT_ID';       // The primary key for the model.
    protected $casts = [
        'TARGET_USER_ALERT' => 'array',
        'SOURCE_DEVICE_CONDITION' => 'array'
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
}
