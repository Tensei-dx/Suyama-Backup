<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> Notification
 *
 * <Function Name> Notification Model<br>
 * Create : 2018.08.29 TP Robert<br>
 * Update : 2019.06.11 TP Harvey    Applying Coding Standard
 *          2020.05.12 TP Uddin     Implement coding standard for PHP7
 *          2020.05.13 TP Uddin     Added userNotification and room relationship<br>
 * <Overview> This model represents the Notification and its relationship with other models.
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class Notification extends Model
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // __construct                     (0.0) Reassign properties inherited from parent class
    // userNotification                (1.0) Notification hasMany UserNotification relationship
    // room                            (2.0) Notification belongsTo Room relationship

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'T003_NOTIFICATION';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'NOTIFICATION_ID';

    /**
     * The name of the "created at" column<br>
     *
     * @var string
     */
    const CREATED_AT = 'CREATED_AT';

    /**
     * The name of the "updated at" column<br>
     *
     * @var string
     */
    const UPDATED_AT = 'UPDATED_AT';

    /**
     * <layer number> (1.0)
     *
     * <Processing name> userNotification <br>
     * <Function> The user-notifications associated to the notification <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userNotification()
    {
        return $this->hasMany('App\Models\UserNotification', 'NOTIFICATION_ID', 'NOTIFICATION_ID');
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> room <br>
     * <Function> The room associated to the notification <br>
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room()
    {
        return $this->belongsTo('App\Models\Room', 'ROOM_ID', 'ROOM_ID');
    }
}
