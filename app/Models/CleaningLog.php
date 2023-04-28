<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CleaningLog extends Model
{
    /**
     * The table asscoiated with the model.
     * 
     * @var string
     */
    protected $table = 'T012_CLEANING_LOG';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'CLEANING_LOG_ID';

    /**
     * <Layer number> (1.0)
     * 
     * <Processing name> statusCode <br>
     * <Function> The status code associated to the CleaningLog <br>
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statusCode()
    {
        return $this->belongsTo('App\Models\StatusCode', 'STATUS_ID', 'STATUS_ID');
    }
}
