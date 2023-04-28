<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Correspondence extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'T016_CORRESPONDENCE';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'CORRESPONDENCE_ID';

    // /**
    //  * <Layer number> (1.0)
    //  *
    //  * <Processing name> logs_notif <br>
    //  * <Function> Correspondence belongs to Logs_Notification relationship<br>
    //  *
    //  * @return mixed
    //  */
    // public function logs_notif()
    // {
    //     return $this->belongsTo('App\Models\LogsNotification', 'LOGS_NOTIF_ID', 'LOGS_NOTIF_ID');
    // }
}
