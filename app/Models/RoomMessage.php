<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomMessage extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'M031_ROOM_MESSAGES';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'MESSAGE_ID';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * <Layer number> (1.0)
     * 
     * <Processing name> bookRooms <br>
     * <Function> The book rooms associated to the room message <br>
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookRooms()
    {
        return $this->hasMany('App\Models\Book_Room', 'MESSAGE_ID', 'MESSAGE_ID');
    }
}
