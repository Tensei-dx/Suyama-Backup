<?php
/*
 * <System Name> iBMS
 * <Program Name> ProcessedData.php
 *
 * <Create> 2018.06.26 TP Bryan
 * <Update> 2018.07.10 TP Bryan     Defined table/primaryKey property through constructor
 *          2018.08.20 TP Bryan     Fixed code structure
 *          2020.05.12 TP Uddin     Implement coding standard for PHP7
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> ProcessedData
 *
 * <Overview> Class that interacts with the database.
 *
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class ProcessedData extends Model
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // device                   (1.0) ProcessedData belongsTo Device Relationship

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'T001_PROCESSED_DATA';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'PROCESSED_DATA_ID';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'DATA' => 'array'
    ];

    /**
     * The attributes that are mass assignable.
     * 
     * @var array<string>
     */
    protected $fillable = [
        'DEVICE_ID',
        'DATA',
        'SEND_FLAG'
    ];

    /**
     * <Layer number> (1.0)
     * 
     * <Processing name> device <br>
     * <Function> The device associated to the processed data <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function device()
    {
        return $this->belongsTo('App\Models\Device', 'DEVICE_ID', 'DEVICE_ID');
    }
}
