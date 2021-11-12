<?php

namespace App\Models\Signage;

use Illuminate\Database\Eloquent\Model;

use App\Models\Signage\Video;

class Display extends Model
{

    protected $table = 'signage_display';

    protected $fillable = [
        'device_id', 'display_type', 'display_id'
    ];
}
