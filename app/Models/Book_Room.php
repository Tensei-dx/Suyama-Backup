<?php

namespace App\Models;

use App\Services\StayseeReservationService;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * <Class Name> Room
 *
 * <Function Name> Room Model<br>
 * Create : 2021.07.14 TP Harvey<br>
 * Update : 2018.07.02 TP Harvey     Added "Processing Hierarchy"
 */
class Book_Room extends Model
{
    /**
     * The table assciated with the model.
     *
     * @var string
     */
    protected $table = 'T012_BOOK_ROOM';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'BOOK_ROOM_ID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'BOOK_ID',
        'ROOM_ID',
        'USER_ID',
        'CHECK_IN_TIME',
        'CHECK_OUT_TIME',
        'ARRIVAL_TIME',
        'DEPARTURE_TIME',
        'PIN',
        'ACTIVE',
        'REMOTE_LOCK_GUEST_UUID',
        'MESSAGE_ID'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'ACCESS_STARTS_AT',
        'ACCESS_ENDS_AT',
        'RESERVATION_STATUS',
        'LATE_FOR_CHECKOUT',
        'LATE_FOR_CHECKIN'
    ];

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> book <br>
     * <Function> Get the booking associated to the booking room<br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function book()
    {
        return $this->belongsTo('App\Models\BookPMS', 'BOOK_ID', 'BOOK_ID');
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> room <br>
     * <Function> Get the room associated to the booking room<br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room()
    {
        return $this->belongsTo('App\Models\Room', 'ROOM_ID', 'ROOM_ID');
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> user <br>
     * <Function> Get the user associated to the booking room<br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'USER_ID', 'USER_ID');
    }


    /**
     * <Layer number> (4.0)
     *
     * <Processing name> roomMessage <br>
     * <Function> The room message associated to the Book_Room <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function roomMessage()
    {
        return $this->belongsTo('App\Models\RoomMessage', 'MESSAGE_ID', 'MESSAGE_ID');
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> getAccessStartsAtAttribute <br>
     * <Function> Create an accessor/getter "ACCESS_STARTS_AT" that calculates
     *            the proper start time when the guest can access the Remote
     *            Lock device
     *
     * @return \Carbon\CarbonImmutable
     */
    public function getAccessStartsAtAttribute()
    {
        // get the environment variables
        $checkInBufferInMinutes = config('staysee.buffer_check_in_time');
        $defaultCheckInHMS = Str::of(config('staysee.hotel_check_in_time'))
            ->explode(':');

        // parse the check in time of the booking
        $checkInTime = CarbonImmutable::parse($this->attributes['CHECK_IN_TIME']);

        // get the check in time with the calculated buffer time
        $checkInTimeWithBuffer = $checkInTime
            ->setTime(...$defaultCheckInHMS)
            ->subMinutes($checkInBufferInMinutes);

        return $checkInTime->isBefore($checkInTimeWithBuffer)
            ? $checkInTime
            : $checkInTimeWithBuffer;
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> getAccessEndsAtAttribute <br>
     * <Function> Create an accessor/getter "ACCESS_ENDS_AT" that calculates the
     *            proper end time when the guest can access the Remote Lock
     *            device
     *
     * @return \Carbon\CarbonImmutable
     */
    public function getAccessEndsAtAttribute()
    {
        // get the environment variables
        $checkOutBufferInMinutes = config('staysee.buffer_check_out_time');
        $defaultCheckOutHMS = Str::of(config('staysee.hotel_check_out_time'))
            ->explode(':');

        // parse the check out time of the booking
        $checkOutTime = CarbonImmutable::parse($this->attributes['CHECK_OUT_TIME']);

        // get the check out time with the calculated buffer time
        $checkOutTimeWithBuffer = $checkOutTime
            ->setTime(...$defaultCheckOutHMS)
            ->addMinutes($checkOutBufferInMinutes);

        return $checkOutTime->isAfter($checkOutTimeWithBuffer)
            ? $checkOutTime
            : $checkOutTimeWithBuffer;
    }

    /**
     * <Layer number> (7.0)
     *
     * <Processing name> getLateForCheckInAttribute <br>
     * <Function> Returns true only if the booking is in RESERVED status but
     *            check in time had already passed <br>
     *
     * @return bool
     */
    public function getLateForCheckInAttribute()
    {
        if (!in_array($this->attributes['STATUS'], StayseeReservationService::STATUS_RESERVED)) {
            return false;
        }

        return Carbon::parse($this->attributes['CHECK_IN_TIME'])->isBefore(now());
    }

    /**
     * <Layer number> (8.0)
     *
     * <Processing name> getLateForCheckOutAttribute <br>
     * <Function> Returns true only if the booking is in CHECKED-IN status but
     *            check out time had already passed <br>
     *
     * @return bool
     */
    public function getLateForCheckOutAttribute()
    {
        return Carbon::parse($this->attributes['CHECK_OUT_TIME'])->isAfter(now());
    }

    /**
     * <Layer number> (9.0)
     *
     * <Processing name> getReservationStatusAttribute <br>
     * <Function> Interpret the STATUS attribute <br>
     *
     * @return int
     */
    public function getReservationStatusAttribute()
    {
        $rsvService = new StayseeReservationService;

        $status = $this->attributes['STATUS'];

        if (in_array($status, $rsvService::STATUS_RESERVED)) return 205;

        if (in_array($status, $rsvService::STATUS_CHECK_IN)) return 201;

        if (in_array($status, $rsvService::STATUS_CHECK_OUT)) return 202;

        return 203;
    }
}
