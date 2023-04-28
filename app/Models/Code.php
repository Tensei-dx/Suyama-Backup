<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * <Class name> Code
 *
 * <Function Name> Code Model<br>
 * Create : 2018.06.20 TP Bryan<br>
 * Update : 2018.06.25 TP Bryan
 *          2018.07.10 TP Bryan    Defined table/primaryKey property through constructor
 *          2018.08.20 TP Bryan    Fixed code structure<br>
 *
 * <Overview> This model represents the Code and its relationship with other models.
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class Code extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'M007_CODE';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'CODE_ID';
}
