<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> Logs
 *
 * <Function Name> Logs Model<br>
 * Create : 2018.12.17 TP Robert<br>
 * Update : 2019.06.11 TP Harvey   Applying Coding Standard<br>
 *
 * <Overview> Class that interacts with the database.
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class Logs extends Model
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // __construct                  (0.0) Reassign properties inherited from parent class
    // user                         (1.0) Logs belongsTo User relationship

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'T002_LOGS';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'LOGS_ID';

    /**
     * The name of the "updated at" column<br>
     *
     * @var null
     */
    const UPDATED_AT = null;

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> user <br>
     * <Function> The user associated to the log <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'HOST', 'USER_ID');
    }
}
