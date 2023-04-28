<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> NatureRemoAccount
 *
 * Create : 2021.10.28 TP Uddin<br>
 *
 * <Overview> This model represents the Nature Remo Accounts table <br>
 * @package model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright
 */
class NatureRemoAccount extends Model
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // natureRemoDevices        (1.0) Get the devices associated with the account
    // natureRemoAppliances     (2.0) Get the appliances associated with the devices of the account

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'M027_NATURE_REMO_ACCOUNTS';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'ACCOUNT_ID';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'ACCOUNT_NAME',
        'ACCESS_TOKEN'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'ACCESS_TOKEN'
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
     * <Processing name> natureRemoDevices <br>
     * <Function> Get the devices associated with the account <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function natureRemoDevices()
    {
        return $this->hasMany('App\Models\NatureRemoDevice', 'ACCOUNT_ID', 'ACCOUNT_ID');
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> natureRemoAppliances <br>
     * <Function> Get the appliances associated with the devices of the account <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function natureRemoAppliances()
    {
        return $this->hasManyThrough(
            'App\Models\NatureRemoAppliance',
            'App\Models\NatureRemoDevice',
            'ACCOUNT_ID',
            'DEVICE_ID',
            'ACCOUNT_ID',
            'DEVICE_ID'
        );
    }
}
