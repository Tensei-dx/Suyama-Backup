<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogsNotification extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'T015_LOGS_NOTIFICATION';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'LOGS_NOTIF_ID';

    /**
     * The attributes that are mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'MESSAGE_ID',
        'ROOM_ID',
        'RESERVATION_ID',
        'EVENT_STATUS'
    ];

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> room <br>
     * <Function> The room associated to the log notification <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room()
    {
        return $this->belongsTo('App\Models\Room', 'ROOM_ID', 'ROOM_ID');
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> correspondence <br>
     * <Function> The correspondence associated to the log notification <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function correspondence()
    {
        return $this->belongsTo('App\Models\Correspondence', 'LOGS_NOTIF_ID', 'LOGS_NOTIF_ID');
    }

    /**
     * <Layer number> (3.0)
     * 
     * <Processing name> reservation <br>
     * <Function> The reservation associated to the log notification <br>
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reservation()
    {
        return $this->belongsTo('App\Models\BookPMS', 'RESERVATION_ID', 'BOOK_ID');
    }
}
