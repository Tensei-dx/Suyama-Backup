<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> AuthModule
 *
 * <Function Name> AuthModule Model<br>
 * Create : 2018.11.15 TP Raymond<br>
 * Update : <br>
 *
 * <Overview> This model represents the AuthModule and its relationship with other models.
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class AuthModule extends Model
{
    /*******************************************************************/
    /* Processing Hierarchy                                            */
    /*******************************************************************/
    // __construct                     (0.0) Reassign properties inherited from parent class
    // users                           (1.0) AuthModule belongsTo User relationship
    // modules                         (2.0) AuthModule belongsTo SystemModule relationship

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'M014_AUTH_MODULE';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'AUTH_MODULE_ID';

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

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> users <br>
     * <Function> The users associated to the AuthModule <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->belongsTo('App\Models\User', 'USER_ID', 'USER_ID');
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> modules <br>
     * <Function> The modules associated to the Module <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modules()
    {
        return $this->belongsTo('App\Models\SystemModule', 'MODULE_ID', 'MODULE_ID');
    }
}
