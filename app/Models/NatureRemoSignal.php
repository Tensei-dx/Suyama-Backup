<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> NatureRemoSignal
 *
 * <Function Name> Nature Remo Signal Model<br>
 * Create : 2021.10.20 TP Uddin<br>
 *
 * <Overview>
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright
 */
class NatureRemoSignal extends Model
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // natureRemoAppliance      (1.0) Get the associated appliance with the signal
    // scopeOfGroup             (2.0) Filter query by SIGNAL_GROUP attribute

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'M030_NATURE_REMO_SIGNALS';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'SIGNAL_ID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'SIGNAL_UUID',
        'SIGNAL_NAME',
        'SIGNAL_LABEL',
        'SIGNAL_GROUP',
        'SIGNAL_DATA',
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
     * <Processing name> natureRemoAppliance <br>
     * <Function> Get the associated appliance with the signal <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function natureRemoAppliance()
    {
        return $this->belongsTo('App\Models\NatureRemoAppliance', 'APPLIANCE_ID', 'APPLIANCE_ID');
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> scopeOfGroup <br>
     * <Function> Filter query by SIGNAL_GROUP attribute <br>
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $group
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfGroup(Builder $query, string $group)
    {
        return $query->where('SIGNAL_GROUP', $group);
    }
}
