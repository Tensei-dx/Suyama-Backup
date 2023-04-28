<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> Manufacturer
 *
 * <Function Name> Manufacturer Model<br>
 * Create : 2018.08.08 TP Bryan<br>
 * Update : 2018.08.20 TP Bryan     Fixed code structure
 *          2019.06.11 TP Harvey    Applying Coding Standard
 *          2020.05.12 TP Uddin     Implement coding standard for PHP7<br>
 *
 * <Overview> This model represents the Manufacturer and its relationship with other models.
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class Manufacturer extends Model
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // __construct                 (0.0) Reassign properties inherited from parent class
    // gateways                    (1.0) Manufacturer hasMany Gateways relationship
    // devices                     (2.0) Manufacturer hasMany Devices relationship
    // codes                       (3.0) Manufacturer hasMany Codes relationship

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'M010_MANUFACTURER';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'MANUFACTURER_ID';

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> gateways <br>
     * <Function> the gateways associated to the manufacturer <br>
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gateways()
    {
        return $this->hasMany('App\Models\Gateway', 'MANUFACTURER_ID', 'MANUFACTURER_ID');
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> devices <br>
     * <Function> The device associated to the manufacturer <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function devices()
    {
        return $this->hasMany('App\Models\Device', 'MANUFACTURER_ID', 'MANUFACTURER_ID');
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> codes <br>
     * <Function> The codes associated to the manufacturer <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function codes()
    {
        return $this->hasMany('App\Models\Code', 'MANUFACTURER_ID', 'MANUFACTURER_ID');
    }
}
