<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> NatureRemoAppliance
 *
 * <Function Name> Nature Remo Appliance Model<br>
 * Create : 2021.10.20 TP Uddin<br>
 *
 * <Overview>
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright
 */
class NatureRemoAppliance extends Model
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // natureRemoDevice         (1.0) Get the device associated with the appliance
    // natureRemoSignals        (2.0) Get the signals associated with the appliance
    // scopeOfType              (3.0) Filter query by APPLIANCE_TYPE attribute
    // scopeRemoved             (4.0) Filter query by NEW_FLAG attribute

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'M029_NATURE_REMO_APPLIANCES';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'APPLIANCE_ID';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'DEVICE_ID',
        'APPLIANCE_UUID',
        'APPLIANCE_TYPE',
        'APPLIANCE_NAME',
        'APPLIANCE_SETTINGS',
        'NEW_FLAG'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'NEW_FLAG' => true,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'APPLIANCE_SETTINGS' => 'array',
        'NEW_FLAG' => 'boolean'
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
     * <Processing name> natureRemoDevice <br>
     * <Function> Get the device associated with the appliance <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function natureRemoDevice()
    {
        return $this->belongsTo('App\Models\NatureRemoDevice', 'DEVICE_ID', 'DEVICE_ID');
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> natureRemoSignals <br>
     * <Function> Get the signals associated with the appliance <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function natureRemoSignals()
    {
        return $this->hasMany('App\Models\NatureRemoSignal', 'APPLIANCE_ID', 'APPLIANCE_ID');
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> scopeOfType <br>
     * <Function> Filter query by APPLIANCE_TYPE attribute <br>
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType(Builder $query, string $type)
    {
        return $query->where('APPLIANCE_TYPE', $type);
    }

    /**
     * <Layer number> (4.0)
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
}
