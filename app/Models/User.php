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



    /**
     * Set the user's Password
     *
     * @param  string  $value
     * @return void
     */
    protected function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::isHashed($value) ? $value : Hash::password($value);
    }
    protected function getPasswordAttribute($value)
    {
        return $this->attributes['password'];
    }
    /**
     * Get the user's name.
     *
     * @param  string  $value
     * @return string
     */
    protected function getNameAttribute($value)
    {
        return ucfirst($value);
    }
}
