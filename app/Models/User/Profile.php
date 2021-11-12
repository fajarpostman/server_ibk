<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';

    protected $fillable = [
        'user_email', 'id_number', 'first_name', 'last_name', 'phone_number'
    ];
}
