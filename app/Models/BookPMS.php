<?php

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Model;

class BookPMS extends Model
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // bookingRooms             (1.0) Get the booking rooms that are associated to this booking
    // bookingsWithRoom         (2.0) Get the booking rooms with the room details
    // bookingRoomAndUser       (3.0) Get the booking rooms with the room and user details

    use EloquentJoin;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'T010_BOOK';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'BOOK_ID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'BOOK_ID',
        'CONTACT_NUMBER',
        'EMAIL',
        'FIRST_NAME',
        'LAST_NAME',
        'ADDRESS',
        'READY_TO_SEND_FLAG',
        'PAID_FLAG',
        'BOOKING_CREATED_MAIL_SENT_FLAG',
        'THANK_YOU_MAIL_SENT_FLAG',
        'PMS_UPDATED_AT',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'LAST_NAME' => null,
        'READY_TO_SEND_FLAG' => false,
        'PAID_FLAG' => false,
        'BOOKING_CREATED_MAIL_SENT_FLAG' => false,
        'THANK_YOU_MAIL_SENT_FLAG' => false
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'READY_TO_SEND_FLAG' => 'boolean',
        'PAID_FLAG' => 'boolean',
        'BOOKING_CREATED_MAIL_SENT_FLAG' => 'boolean',
        'THANK_YOU_MAIL_SENT_FLAG' => 'boolean'
    ];

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> bookingRooms <br>
     * <Function> Get the booking rooms that are associated to this booking <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingRooms()
    {
        return $this->hasMany('App\Models\Book_Room', 'BOOK_ID', 'BOOK_ID');
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> bookingsWithRoom <br>
     * <Function> Get the booking rooms with the room details<br>
     *
     * @return mixed
     */
    public function bookingsWithRoom()
    {
        return $this->hasMany('App\Models\Book_Room', 'BOOK_ID', 'BOOK_ID')->with('room', 'user');
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> bookingRoomAndUser <br>
     * <Function> Get the booking rooms with the room and user details <br>
     *
     * @return mixed
     */
    public function bookingRoomAndUser()
    {
        return $this->hasMany('App\Models\Book_Room', 'BOOK_ID', 'BOOK_ID')->with('room', 'user');
    }

    /**
     * Get the guest cards associated to the booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function guestCards()
    {
        return $this->hasMany('App\Models\GuestCard', 'BOOK_ID', 'BOOK_ID');
    }
}
