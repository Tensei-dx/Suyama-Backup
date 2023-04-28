<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> Device
 *
 * <Function Name> Device Model<br>
 * Create : 2018.06.20 TP Bryan<br>
 * Update : 2018.06.25 TP Bryan    Added floor()
 *          2018.06.29 TP Bryan    Fixed comments
 *          2018.07.02 TP Bryan    Added "Processing Hierarchy"
 *          2018.07.10 TP Bryan    Defined table/primaryKey property through constructor
 *          2018.07.30 TP Bryan    Added "casts" property
 *          2018.08.20 TP Bryan    Fixed code structure
 *          2018.11.20 TP Robert   Add New Function for device bindings
 *          2021.10.08 TP Uddin    Added scope for filtering device type
 *          2021.10.08 TP Uddin    Added manufacturer relationship<br>
 *
 * <Overview> This model represents the Device and its relationship with other models.
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class Device extends Model
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // __construct                      (0.0) Reassign properties inherited from parent class
    // floor                            (1.0) Device belongsTo Floor Relationship
    // room                             (2.0) Device belongsTo Room relationship
    // gateway                          (3.0) Device belongsTo Gateway relationship
    // processedData                    (4.0) Device hasMany ProcessedData relationship
    // bindings                         (5.0) Device hasMany Source Device Binding relationship
    // deviceBindings                   (6.0) Device hasMany Target Device Binding relationship
    // getSearchableColumns             (7.0) Get columns that will be used for searching
    // bindingAlerts                    (8.0) Device hasMany Source Device Binding relationship
    // bindingCameraSource              (9.0) Device hasMany Source Camera Binding relationship
    // bindingCamera                    (10.0) Device hasMany Camera Binding relationship
    // irLearningList                   (11.0) Returns the IR learning associated to the device
    // natureRemoAppliances             (12.0) Returns the Nature Remo Appliances associated to the device
    // scopeOfType                      (13.0) Scope a query to only include a specific device type
    // manufacturer                     (14.0) Device belongsTo Manufacturer relationship
    // natureRemoSignals                (15.0) Get the signals associated to all the appliances of the device
    // scopeRegistered                  (16.0) Filter the query by REG_FLAG attribute
    // scopeOnline                      (17.0) Filter the query by ONLINE_FLAG attribute
    // spareKey                         (18.0) The spareKey associated to the Remote Lock device

    use EloquentJoin;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'M006_DEVICE';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'DEVICE_ID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'FLOOR_ID',
        'ROOM_ID',
        'GATEWAY_ID',
        'MANUFACTURER_ID',
        'DEVICE_SERIAL_NO',
        'DEVICE_TYPE',
        'DEVICE_CATEGORY',
        'DATA',
        'DEVICE_NAME',
        'DEVICE_MAP_NAME',
        'EMERGENCY_DEVICE',
        'REG_FLAG',
        'ONLINE_FLAG',
        'LOW_VOLTAGE',
        'POWER_LEVEL'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'DATA' => 'array'
    ];

    /**
     * The columns that will be used for searching
     *
     * @var array
     */
    protected $searchableColumns = [
        'DEVICE_SERIAL_NO',
        'DEVICE_TYPE',
        'DEVICE_NAME'
    ];

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> floor <br>
     * <Function> The floor associated to the device <br>
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
     * <Processing name> room <br>
     * <Function> The room associated to the device <br>
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
     * <Processing name> gateway <br>
     * <Function> The gateway associated to the device <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gateway()
    {
        return $this->belongsTo('App\Models\Gateway', 'GATEWAY_ID', 'GATEWAY_ID');
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> processedData <br>
     * <Function> The processedData associated to the device <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function processedData()
    {
        return $this->hasMany('App\Models\ProcessedData', 'DEVICE_ID', 'DEVICE_ID');
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> bindings <br>
     * <Function> The bindings associated to the device <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bindings()
    {
        return $this->hasMany('App\Models\Binding', 'SOURCE_DEVICE_ID', 'DEVICE_ID');
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> deviceBindings <br>
     * <Function> The device bindings associated to the device <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deviceBindings()
    {
        return $this->hasMany('App\Models\Binding', 'TARGET_DEVICE_ID');
    }

    /**
     * <Layer number> (7.0)
     *
     * <Processing name> getSearchableColumns <br>
     * <Function> The attributes that can be used for searching <br>
     *
     * @return array
     */
    public function getSearchableColumns()
    {
        return $this->searchableColumns;
    }

    /**
     * <Layer number> (8.0)
     *
     * <Processing name> bindingAlerts <br>
     * <Function> The bindingAlerts associated to the device <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bindingAlerts()
    {
        return $this->hasMany('App\Models\BindingAlert', 'SOURCE_DEVICE_ID', 'DEVICE_ID');
    }

    /**
     * <Layer number> (9.0)
     *
     * <Processing name> bindingCameraSource <br>
     * <Function> The bindingCameraSource associated to the device <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bindingCameraSource()
    {
        return $this->hasMany('App\Models\BindingCamera', 'TARGET_DEVICE_ID', 'DEVICE_ID');
    }

    /**
     * <Layer number> (10.0)
     *
     * <Processing name> bindingCamera <br>
     * <Function> The bindingCameras associated to the device <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bindingCamera()
    {
        return $this->hasMany('App\Models\BindingCamera', 'SOURCE_DEVICE_ID', 'DEVICE_ID');
    }

    /**
     * <Layer number> (11.0)
     *
     * <Processing name> irLearningList <br>
     * <Function> The IR learning list associated to the device <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function irLearningList()
    {
        return $this->hasMany('App\Models\IrLearning', 'DEVICE_ID', 'DEVICE_ID');
    }

    /**
     * <Layer number> (12.0)
     *
     * <Processing name> natureRemoAppliance <br>
     * <Function> The Nature Remo Appliance associated to the device <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function natureRemoAppliances()
    {
        return $this->hasMany('App\Models\NatureRemoAppliance', 'DEVICE_ID', 'DEVICE_ID');
    }

    /**
     * <Layer number> (13.0)
     *
     * <Processing name> scopeOfType
     * <Function> Scope a query to only include a specific device type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType(Builder $query, string $type)
    {
        return $query->where('DEVICE_TYPE', $type);
    }

    /**
     * <Layer number> (14.0)
     *
     * <Processing name> manufacturer <br>
     * <Function> The manufacturer associated to the device <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manufacturer()
    {
        return $this->belongsTo('App\Models\Manufacturer', 'MANUFACTURER_ID', 'MANUFACTURER_ID');
    }

    /**
     * <Layer number> (15.0)
     *
     * <Processing name> natureRemoSignals <br>
     * <Function> Get the signals associated to all the appliances of the device <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function natureRemoSignals()
    {
        return $this->hasManyThrough(
            'App\Models\NatureRemoSignal',
            'App\Models\NatureRemoAppliance',
            'DEVICE_ID',
            'APPLIANCE_ID',
            'DEVICE_ID',
            'APPLIANCE_ID'
        );
    }

    /**
     * <Layer number> (16.0)
     *
     * <Processing name> scopeRegistered <br>
     * <Function> Filter the query by REG_FLAG attribute <br>
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRegistered(Builder $query, bool $value = true)
    {
        return $query->where('REG_FLAG', (int) $value);
    }

    /**
     * <Layer number> (17.0)
     *
     * <Processing name> scopeOnline <br>
     * <Function> Filter the query by ONLINE_FLAG attribute <br>
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnline(Builder $query, bool $value = true)
    {
        return $query->where('ONLINE_FLAG', (int) $value);
    }

    /**
     * <Layer number> (18.0)
     *
     * <Processing name> spareKey <br>
     * <Function> The spareKey associated to the Remote Lock device <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function spareKey()
    {
        return $this->hasOne('App\Models\SpareKey', 'REMOTE_LOCK_DEVICE_ID', 'DEVICE_ID');
    }

    /**
     * <Layer number> (19.0)
     *
     * <Processing name> latestYesterdayData <br>
     * <Function> Get the latest processed data since yesterday <br>
     *      This relationship is used by the People Counter application to
     *      monitor the remaining data before the counter is reset everyday.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestYesterdayData()
    {
        return $this->hasOne('App\Models\ProcessedData', 'DEVICE_ID', 'DEVICE_ID')
            ->whereDate('CREATED_AT', '<=', now()->subDay())->latest();
    }

    /**
     * <Layer number (20.0)
     *
     * <Processing name> getPeopleCountAttribute <br>
     * <Function> Compute the actual people count data of the camera <br>
     *
     * @return int|null
     */
    public function getPeopleCountAttribute(): ?int
    {
        if ($this->attributes['DEVICE_TYPE'] !== 'camera') {
            return null;
        }

        $data = json_decode($this->attributes['DATA'], true);

        // $totalIn = (int) $data['peopleIn'] + (int) $data['yesterdayIn'];
        // $totalOut = (int) $data['peopleOut'] + (int) $data['yesterdayOut'];
        $totalIn = (int) $data['peopleIn'];
        $totalOut = (int) $data['peopleOut'];

        return $totalIn - $totalOut;
    }
}
