<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppLogs extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'T014_APPLOGS';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'APPLOGS_ID';

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
     * <Function> The user associated to the appLog <br>
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'USER_ID', 'USER_ID');
    }
}
