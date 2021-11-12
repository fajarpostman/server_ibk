<?php

namespace App\Models\Signage;

use Illuminate\Database\Eloquent\Model;

class Deposito extends Model
{
    protected $table = 'signage_depositos';

    protected $fillable = [
        'tenor', 'interest',
    ];
}
