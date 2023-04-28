<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use App\Services\StayseeReservationService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> Room
 *
 * <Function Name> Room Model<br>
 * Create : 2018.06.25 TP Bryan<br>
 * Update : 2018.07.02 TP Bryan     Added "Processing Hierarchy"
 *          2018.07.10 TP Bryan     Defined table/primaryKey property through constructor
 *          2018.08.20 TP Bryan     Fixed code structure
 *          2018.12.13 TP Robert    add new function for register gateway and devices
 *          2020.05.12 TP Uddin     Implement coding standard for PHP7
 *          2020.05.13 TP Uddin     Added notification relationship<br>
 *          2021.07.14 TP Harvey    book_room implementation
 *
 * <Overview> This model represents the Room and its relationship with other models.
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class Room extends Model
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // __construct                  (0.0) Reassign properties inherited from parent class
    // floor                        (1.0) Room belongsTo Floor relationship
    // gateways                     (2.0) Room hasMany Gateways relationship
    // registeredGateways           (3.0) Room hasMany Registered Wulian Gateways relationship
    // devices                      (4.0) Room hasMany Devices Relationship
    // cleaningLog                  (5.0) The cleaning logs associated to the room
    // statusCode                   (6.0) The status code associated to the room
    // taskList                     (7.0) The task lists associated to the room
    // regDevices                   (8.0) Room hasMany Registered Devices relationship
    // getSearchableColumns         (9.0) Get columns that will be used for searching
    // onDevices                    (10.0) Room hasMany Online Devices relationship
    // meters                       (11.0) Room hasMany Registered Electric Meter Relationship
    // notification                 (12.0) Room hasMany Notification relationship
    // bookingsWithBook             (13.0) Room hasMany Book_Room relationship
    // statusName                   (14.0) The status code associated to the room
    // bookingToday                 (15.0) Get the booking today of the room
    // bookingNow                   (16.0) Get the current booking of the room
    // checkOutToday                (17.0) Get the booking that is scheduled to check out today in this room
    // checkInToday                 (18.0) Get the booking that is scheduled to check in today in this room
    // futureBookings               (19.0) Get the future bookings associated to the room
    // natureRemoDevices            (20.0) Get the Nature Remo devices associated to the room
    // natureRemoAppliances         (21.0) Get the Nature Remo appliances associated to the Nature Remo devices of the room
    // remotelockDevices            (22.0) The remote lock devices associated to the room

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'M004_ROOM';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'ROOM_ID';

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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'FLOOR_ID',
        'ROOM_NAME',
        'MAX_OCCUPANCY',
        'STATUS_ID',
        'EMERGENCY_FLAG'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'ROOM_MAP_DATA' => 'array',
        'EMERGENCY_FLAG' => 'boolean'
    ];

    /**
     * The columns that will be used for searching<br>
     *
     * @var array
     */
    protected $searchableColumns = [
        'ROOM_NAME'
    ];

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> floor <br>
     * <Function> The floor associated to the room <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function floor()
    {
        return $this->belongsTo('App\Models\Floor', 'FLOOR_ID', 'FLOOR_ID');
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name> gateways <br>
     * <Function> The gateways associated to the room <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gateways()
    {
        return $this->hasMany('App\Models\Gateway', 'ROOM_ID', 'ROOM_ID');
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> registeredGateways <br>
     * <Function> The registered gateways associated to the room <br>
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function registeredGateways()
    {
        return $this->hasMany('App\Models\Gateway', 'ROOM_ID', 'ROOM_ID')
            ->where('REG_FLAG', 1)
            ->where('MANUFACTURER_ID', 1);
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> devices <br>
     * <Function> The devices associated to the room <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function devices()
    {
        return $this->hasMany('App\Models\Device', 'ROOM_ID', 'ROOM_ID');
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> cleaningLog <br>
     * <Function> The cleaning logs associated to the room <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cleaningLog()
    {
        return $this->hasMany('App\Models\CleaningLog', 'ROOM_ID', 'ROOM_ID');
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> statusCode <br>
     * <Function> The status code associated to the room <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statusCode()
    {
        return $this->belongsTo('App\Models\StatusCode', 'STATUS_ID', 'STATUS_ID');
    }

    /**
     * <Layer number> (7.0)
     *
     * <Processing name> taskList <br>
     * <Function> The task lists associated to the room <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taskList()
    {
        return $this->hasMany('App\Models\TaskList', 'ROOM_ID', 'ROOM_ID');
    }

    /**
     * <Layer number> (8.0)
     *
     * <Processing name> regDevices <br>
     * <Function> The registered devices associated to the room <br>
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function regDevices()
    {
        return $this->hasMany('App\Models\Device', 'ROOM_ID', 'ROOM_ID')
            ->where('REG_FLAG', 1);
    }

    /**
     * <Layer number> (9.0)
     *
     * <Processing name> getSearchableColumns <br>
     * <Function> Get the attributes that will be used for searching <br>
     *
     * @return array
     */
    public function getSearchableColumns()
    {
        return $this->searchableColumns;
    }

    /**
     * <Layer number> (10.0)
     *
     * <Processing name> onDevices <br>
     * <Function> The online devices associated to the room <br>
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function onDevices()
    {
        return $this->hasMany('App\Models\Device', 'ROOM_ID', 'ROOM_ID')
            ->where('ONLINE_FLAG', 1);
    }

    /**
     * <Layer number> (12.0)
     *
     * <Processing name> notification <br>
     * <Function> The notifications associated to the room <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notification()
    {
        return $this->hasMany('App\Models\Notification', 'ROOM_ID', 'ROOM_ID');
    }

    /**
     * <Layer number> (13.0)
     *
     * <Processing name> bookingsWithBook <br>
     * <Function> The book room associated to the room with the book information <br>
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function bookingsWithBook()
    {
        return $this->hasMany('App\Models\Book_Room', 'ROOM_ID', 'ROOM_ID')
            ->with('book');
    }

    /**
     * <Layer number> (14.0)
     *
     * <Processing name> statusName <br>
     * <Function> The status code associated to the room <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function statusName()
    {
        return $this->hasOne('App\Models\StatusCode', 'STATUS_ID', 'STATUS_ID');
    }

    /**
     * <Layer number> (15.0)
     *
     * <Processing name> bookingToday <br>
     * <Function> Get the booking today of the room <br>
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function bookingToday()
    {
        return $this->hasOne('App\Models\Book_Room', 'ROOM_ID', 'ROOM_ID')
            ->whereDate('CHECK_IN_TIME', '<=', Carbon::today())
            ->whereDate('CHECK_OUT_TIME', '>=', Carbon::now());
    }

    /**
     * <Layer number> (16.0)
     *
     * <Processing name> bookingNow <br>
     * <Function> Get the current booking of the room <br>
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function bookingNow()
    {
        return $this->hasOne('App\Models\Book_Room', 'ROOM_ID', 'ROOM_ID')
            ->where('CHECK_IN_TIME', '<=', now())
            ->where('CHECK_OUT_TIME', '>=', now());
    }

    /**
     * <Layer number> (17.0)
     *
     * <Processing name> checkOutToday <br>
     * <Function> Get the booking that is scheduled to check out today in this room <br>
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function checkOutToday()
    {
        return $this->hasOne('App\Models\Book_Room', 'ROOM_ID', 'ROOM_ID')
            ->whereDate('CHECK_OUT_TIME', Carbon::today());
    }

    /**
     * <Layer number> (18.0)
     *
     * <Processing name> checkInToday <br>
     * <Function> Get the booking that is scheduled to check in today in this room <br>
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function checkInToday()
    {
        return $this->hasOne('App\Models\Book_Room', 'ROOM_ID', 'ROOM_ID')
            ->whereDate('CHECK_IN_TIME', Carbon::today());
    }

    /**
     * <Layer number> (19.0)
     *
     * <Processing name> futureBookings <br>
     * <Function> Get the future bookings associated to the room <br>
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function futureBookings()
    {
        return $this->hasMany('App\Models\Book_Room', 'ROOM_ID', 'ROOM_ID')
            ->whereDate('CHECK_IN_TIME', '>=', Carbon::today());
    }

    /**
     * <Layer number> (20.0)
     *
     * <Processing name> natureRemoDevices <br>
     * <Function> Get the Nature Remo devices associated to the room <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function natureRemoDevices()
    {
        return $this->hasMany('App\Models\NatureRemoDevice', 'ROOM_ID', 'ROOM_ID');
    }

    /**
     * <Layer number> (21.0)
     *
     * <Processing name> natureRemoAppliances <br>
     * <Function> Get the Nature Remo appliances associated to the Nature Remo devices of the room <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function natureRemoAppliances()
    {
        return $this->hasManyThrough(
            'App\Models\NatureRemoAppliance',
            'App\Models\NatureRemoDevice',
            'ROOM_ID',
            'DEVICE_ID',
            'ROOM_ID',
            'DEVICE_ID'
        );
    }

    /**
     * <Layer number> (22.0)
     *
     * <Processing name> remotelockDevices <br>
     * <Function> The remote lock devices associated to the room <br>
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function remotelockDevices()
    {
        return $this->hasMany('App\Models\Device', 'ROOM_ID', 'ROOM_ID')
            ->where('DEVICE_TYPE', 'remote_lock');
    }

    /**
     * <Layer number> (23.0)
     *
     * <Processing name> currentlyCheckIn <br>
     * <Function> Get the reservation that scheduled to be currently checked in <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currentlyCheckedIn()
    {
        return $this->hasOne('App\Models\Book_Room', 'ROOM_ID', 'ROOM_ID')
            ->where('CHECK_IN_TIME', '<=', now())
            ->where('CHECK_OUT_TIME', '>=', now())
            ->where('STATUS', StayseeReservationService::STATUS_CHECK_IN);
    }
}
