<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


use Illuminate\Notifications\Notifiable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Model
{
    use SoftDeletes;

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'birthday',

        'email', 'phone', 'mobile',

        'street', 'zipcode', 'city',

        'password' ,

        'leave_days', 'hours_of_work',

        'role', 'employment',
    ];


    public function setMobileAttribute( $value )
    {

        if ( trim($value) )
        {
            if ( $value[0] === "0" )
            {
                // remove white spaces
                $number = str_replace(" ", null, $value);
                // add german country number
                $this->attributes['mobile'] = "+49".substr($value, 1 );
            }
        }
    }


    public function scopeBirthdate( $query, $from, $to=null )
    {
        return $query->whereRaw( 'extract(month from birthdate) >= ? AND extract(month from birthdate) <= ?  ', [$from, $to] );
    }



    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
