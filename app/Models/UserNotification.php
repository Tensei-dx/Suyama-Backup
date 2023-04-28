<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> UserNotification
 *
 * <Function Name> User Notification Model<br>
 * Create : 2018.08.29 TP Robert<br>
 * Update : 2020.05.13 TP Uddin     Implement coding standard for PHP7<br>
 *
 * <Overview> Class that interacts with the database.
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class UserNotification extends Model
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // __construct                     (1.0) Reassign properties inherited from parent class
    // userNotification                (2.0) UserNotification hasMany Notification relationship

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'T004_USER_NOTIFICATION';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'USER_NOTIFICATION_ID';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * <layer number> (1.0)
     *
     * <Processing name> userNotification <br>
     * <Function> The notification associated to the user notification <br>
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userNotification()
    {
        return $this->belongsTo('App\Models\Notification', 'NOTIFICATION_ID', 'NOTIFICATION_ID');
    }
}
