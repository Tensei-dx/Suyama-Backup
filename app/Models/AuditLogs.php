<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> AuditLogs
 *
 * <Function Name> AuditLogs Model<br>
 * Create : 2018.12.17 TP Yani<br>
 * Update : <br>
 *
 * <Overview> This model represents the Audit Trail Logs and its relationship with other models.
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class AuditLogs extends Model
{
    /*******************************************************************/
    /* Processing Hierarchy                                            */
    /*******************************************************************/
    // __construct              (0.0) Reassign properties inherited from parent class
    // user                     (1.0) AuditLogs belongsTo User relationship

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'T005_AUDIT_LOGS';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'AUDIT_LOGS_ID';

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
     * <Processing name> user <br>
     * <Function> The user associated to the AuditLog <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'HOST', 'USER_ID');
    }
}
