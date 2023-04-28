<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> Session
 *
 * <Function Name> Session Model<br>
 * Create : 2019.01.23<br>
 * Update : <br>
 *
 * <Overview> This model represents the Session and its relationship with other models.
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class Session extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'L001_SESSION';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'id';
}
