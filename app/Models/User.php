<?php

namespace App\Models;

use TurFramework\Database\Model;
use TurFramework\Support\Hash;

class User extends Model
{


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::password($value);
    }
}
