<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> AuthLocation
 *
 * <Function Name> Auth Location Model<br>
 * Create : 2018.10.5 TP Robert<br>
 * Update : <br>
 *
 * <Overview> This model represents the Auth Location and its relationship with other models.
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class AuthLocation extends Model
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // __construct                      (0.0) Reassign properties inherited from parent class

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'M002_AUTH_LOCATION';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'LOCATION_ID';

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'CREATED_AT';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'UPDATED_AT';
}
