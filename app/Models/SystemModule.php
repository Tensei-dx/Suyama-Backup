<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> SystemModule
 *
 * <Function Name> System Module Model<br>
 * Create : 2018.11.15 TP Raymond<br>
 * Update : 2020.05.12 TP Uddin      Implement coding standard for PHP7<br>
 *
 * <Overview> This model represents the System Module and its relationship with other models.
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class SystemModule extends Model
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // __construct                     (0.0) Reassign properties inherited from parent class
    // authModules                     (1.0) SystemModule hasMany AuthModules relationship

    use EloquentJoin;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'M013_SYSTEM_MODULE';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'MODULE_ID';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> authModules <br>
     * <Function> The auth modules associated to the module <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function authModules()
    {
        return $this->hasMany('App\Models\AuthModule', 'MODULE_ID', 'MODULE_ID');
    }
}
