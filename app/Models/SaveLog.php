<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaveLog extends Model
{
    /**
     * The table associated to the log.
     * 
     * @var string
     */
    protected $table = 'T013_SAVELOGS';

    /**
     * The primary key for the table.
     * 
     * @var string
     */
    protected $primaryKey = 'LOGS_ID';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * <Layer number> (1.0)
     * 
     * <Processing name> user <br>
     * <Function> The user associated to the log <br>
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'USER_ID', 'USER_ID');
    }
}
