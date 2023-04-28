<?php

/**
 * <System Name> iBMS
 */

namespace App\Models;

use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> Api
 *
 * <Function Name> Api Model<br>
 * Create : 2020.12.14 TP Uddin<br>
 * Update : <br>
 *
 * <Overview> This model represents the API and its relationship with other models.
 * @package Model
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class Api extends Model
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // __construct                      (0.0) Reassign properties inherited from parent class
    // ApiToken                         (1.0) Api hasOne ApiToken relationship

    use EloquentJoin;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'M021_API_INFO';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'API_ID';

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> apiToken <br>
     * <Function> The API token associated to the API. <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ApiToken()
    {
        return $this->hasOne('App\Models\ApiToken', 'API_ID', 'API_ID');
    }
}
