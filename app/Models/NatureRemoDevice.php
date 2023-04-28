<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> NatureRemoDevice
 *
 * Create : 2021.10.28 TP Uddin<br>
 *
 * <Overview> This model represents the Nature Remo Device table <br>
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright
 */
class NatureRemoDevice extends Model
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // room                     (1.0) Get the room associated with the device
    // natureRemoAccount        (2.0) Get the account associated with the device
    // natureRemoAppliances     (3.0) Get the appliances associated with the device
    // natureRemoSignals        (4.0) Get the signals associated with the appliances of the device
    // scopeRemoved             (5.0) Filter query by NEW_FLAG attribute
    // scopeRegistered          (6.0) Filter query by REG_FLAG attribute

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'M028_NATURE_REMO_DEVICES';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'DEVICE_ID';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'ROOM_ID',
        'ACCOUNT_ID',
        'DEVICE_UUID',
        'DEVICE_NAME',
        'DEVICE_SERIAL_NO',
        'MAC_ADDRESS',
        'DATA',
        'NEW_FLAG',
        'REG_FLAG'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'NEW_FLAG' => true,
        'REG_FLAG' => false
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'NEW_FLAG' => 'boolean',
        'REG_FLAG' => 'boolean'
    ];

    /**
     * Define custom name for the created_at column.
     *
     * @var string
     */
    const CREATED_AT = 'CREATED_AT';

    /**
     * Define custom name for the updated_at column.
     *
     * @var string
     */
    const UPDATED_AT = 'UPDATED_AT';

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> room <br>
     * <Function> Get the room associated with the device <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room()
    {
        return $this->belongsTo('App\Models\Room', 'ROOM_ID', 'ROOM_ID');
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> natureRemoAccount <br>
     * <Function> Get the account associated with the device <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function natureRemoAccount()
    {
        return $this->belongsTo('App\Models\NatureRemoAccount', 'ACCOUNT_ID', 'ACCOUNT_ID');
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> natureRemoAppliances <br>
     * <Function> Get the appliances associated with the device <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function natureRemoAppliances()
    {
        return $this->hasMany('App\Models\NatureRemoAppliance', 'DEVICE_ID', 'DEVICE_ID');
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> natureRemoSignals <br>
     * <Function> Get the signals associated with the appliances of the device <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function natureRemoSignals()
    {
        return $this->hasManyThrough(
            'App\Models\NatureRemoSignal',
            'App\Models\NatureRemoAppliance',
            'DEVICE_ID',
            'APPLIANCE_ID',
            'DEVICE_ID',
            'APPLIANCE_ID'
        );
    }

    /**
     * <Layer number> (5.0)
     * 
     * <Processing name> scopeRemoved <br>
     * <Function> Filter query by NEW_FLAG attribute <br>
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRemoved(Builder $query, bool $value = true)
    {
        return $query->where('NEW_FLAG', !$value);
    }

    /**
     * <Layer number> (6.0)
     * 
     * <Processing name> scopeRegistered <br>
     * <Function> Filter query by REG_FLAG attribute <br>
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRegistered(Builder $query, bool $value = true)
    {
        return $query->where('REG_FLAG', $value);
    }
}
