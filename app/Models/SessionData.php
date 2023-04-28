<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * <Class Name> SessionData
 *
 * <Function Name> Session Data Model<br>
 * Create : 2019.01.23 OJT Jethro<br>
 * Update : 2020.05.12 TP Uddin     Implement coding standard for PHP7<br>
 *
 * <Overview> This model represents the Session Data and its relationship with other models.
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class SessionData extends Model
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
