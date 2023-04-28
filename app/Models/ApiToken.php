<?php

/**
 * <System Name> iBMS
 */

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> ApiToken
 *
 * <Function Name> API Token Model<br>
 * Create : 2021.01.14 TP Uddin<br>
 * Update : <br>
 *
 * <Overview> This model represents the API Token and its relationship with other models.
 * @package Model
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class ApiToken extends Model
{
    /**************************************************************************/
    /* Processing Heirarchy                                                   */
    /**************************************************************************/
    // __construct                  (0.0) Reassign properties inherited from parent class
    // api

    use EloquentJoin;

    protected $table = 'T010_API_TOKEN';        // The table associated with the model
    protected $primaryKey = 'TOKEN_ID';         // The primary key for the model

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> ApiToken and Api Relationship<br>
     * <Function> ApiToken belongsTo Api relationship<br>
     *
     * @return mixed $this->belongsTo('App\Api', 'API_ID', 'API_ID');
     */
    public function api()
    {
        return $this->belongsTo('App\Models\Api', 'API_ID', 'API_ID');
    }
}
