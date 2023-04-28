<?php
/*
 * <System Name> iBMS
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

/**
 * <Class Name> User
 *
 * <Function Name> User Model<br>
 * Create : 2018.07.09 TP Bryan<br>
 * Update : 2018.07.10 TP Bryan     Defined table/primaryKey property through constructor
 *          2018.11.15 TP Raymond   Add coded for modules
 *          2020.05.13 TP Uddin     Implement coding standard for PHP7<br>
 * Update : 2021.09.14 TDN Renji    SPRINT07 Task141 refactoring related to login
 *
 * <Overview> This model represents the User and its relationship with other models.
 * @package Model
 * @author TP Uddin <u-almujeer@tenseiph.com>
 * @version ver1.0
 * @copyright (c) 2020 GoTensei Inc.
 */
class User extends Authenticatable
{
    /**************************************************************************/
    /* Processing Hierarchy                                                   */
    /**************************************************************************/
    // __construct                      (0.0) Reassign properties inherited from parent class
    // getAuthPassword                  (1.0) Retrieve user password
    // authUserFloor                    (2.0) User hasMany AuthLocation relationship
    // authModules                      (3.0) User hasMany AuthModule relationship
    // bookRoom                         (4.0) User and Book_Room Module Relationship
    // getUserByUsername                (5.0) Get user by username
    // checkPassword                    (6.0) Check the password
    // isValidUser                      (7.0) Check if the user is valid
    // isAdmin                          (8.0) Check if the user is admin
    // isGuest                          (9.0) Check if the user is guest
    // getReservation                   (10.0) Get the reservation data that a user has

    use Notifiable;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'M001_USERS';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'USER_ID';

    /**
     * The attributes that are mass assignable<br>
     *
     * @var array $fillable
     */
    protected $fillable = [
        'LAST_NAME',
        'FIRST_NAME',
        'USERNAME',
        'EMAIL',
        'PASSWORD',
        'CONTACT_NUMBER',
        'ALLOW_ALERT_NOTIFICATION',
        'USER_TYPE',
        'REG_FLAG',
        'USER_LOGO'
    ];

    /**
     * The model's default values for attributes.
     * 
     * @var array 
     */
    protected $attributes = [
        'LAST_NAME' => null,
        'USER_LOGO' => null
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'ALLOW_ALERT_NOTIFICATION' => 'array'
    ];

    /**
     * The attributes that should be hidden for arrays<br>
     *
     * @var array $hidden
     */
    protected $hidden = [
        'PASSWORD', 'REMEMBER_TOKEN',
    ];

    /**
     * <Layer number> (1.0)
     *
     * <Processing name> getAuthPassword <br>
     * <Function> Get the user's password <br>
     *
     * @return string;
     */
    public function getAuthPassword()
    {
        return $this->PASSWORD;
    }

    /**
     * <Layer number> (2.0)
     *
     * <Processing name>  authUserFloor <br>
     * <Function> The auth locations associated to the user <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function authUserFloor()
    {
        return $this->hasMany('App\Models\AuthLocation', 'USER_ID', 'USER_ID');
    }

    /**
     * <Layer number> (3.0)
     *
     * <Processing name> authModules <br>
     * <Function> The auth modules associated to the user <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function authModules()
    {
        return $this->hasMany('App\Models\AuthModule', 'USER_ID', 'USER_ID');
    }

    /**
     * <Layer number> (4.0)
     *
     * <Processing name> bookRoom <br>
     * <Function> The book room associated to the user <br>
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function bookRoom()
    {
        return $this->hasOne('App\Models\Book_Room', 'USER_ID', 'USER_ID');
    }

    /**
     * <Layer number> (5.0)
     *
     * <Processing name> getUserByUsername <br>
     * <Function> Retrieve a user by its username <br>
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\User
     */
    public function getUserByUsername(Request $request)
    {
        return User::where('username', $request->username)
            ->first();
    }

    /**
     * <Layer number> (6.0)
     *
     * <Processing name> checkPassword <br>
     * <Function> Check if the input password matches the user's password <br>
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return bool
     */
    public function checkPassword($request, $user)
    {
        return Hash::check($request->password, $user->PASSWORD);
    }

    /**
     * <Layer number> (7.0)
     *
     * <Processing name> isValidUser <br>
     * <Function> Check if the user is valid <br>
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function isValidUser(User $user)
    {
        return ((bool) $user->REG_FLAG == true);
    }

    /**
     * <Layer number> (8.0)
     *
     * <Processing name> isAdmin <br>
     * <Function> Check if the user is admin <br>
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function isAdmin(User $user)
    {
        return ((int) $user->USER_TYPE === 1);
    }

    /**
     * <Layer number> (9.0)
     *
     * <Processing name> isGuest <br>
     * <Function> Check if the user is guest <br>
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function isGuest(User $user)
    {
        return ((int) $user->USER_TYPE === 2);
    }

    /**
     * <Layer number> (10.0)
     *
     * <Processing name> getReservation <br>
     * <Function> Get the reservation data that a user has <br>
     *
     * @param  \App\User  $user
     * @return \App\Book_Room
     */
    public function getReservation($user)
    {
        return $user->bookRoom ?? null;
    }

    /**
     * The access user associated to the user.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function accessPerson()
    {
        return $this->hasOne('App\Models\AccessPerson', 'USER_ID', 'USER_ID');
    }

    /**
     * Scope a query to only include the specified user type.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType(Builder $query, int $value)
    {
        return $query->where('USER_TYPE', $value);
    }
}
