<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestCard extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'T017_GUESTCARD_DATA';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'GUESTCARD_DATA_ID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'BOOK_ID',
        'BOOK_NO',
        'MEMBERS_ID',
        'MEMBER_TYPE',
        'NAME',
        'SEX',
        'AGE',
        'OCCUPATION',
        'TEL',
        'EMAIL',
        'ADDRESS',
        'PASSPORT_URL',
        'IMAGE',
        'IMAGE_NAME',
        'NATIONALITY',
        'PASSPORT_NO',
        'PREVIOUS_PLACE',
        'NEXT_DESTINATION'
    ];

    /**
     * <Layer number> (1.0)
     * 
     * <Processing name> booking <br>
     * <Function> The booking associated to the guest card <br>
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking()
    {
        return $this->belongsTo('App\Models\BookPMS', 'BOOK_ID', 'BOOK_ID');
    }
}
