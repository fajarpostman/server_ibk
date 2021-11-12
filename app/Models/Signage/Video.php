<?php

namespace App\Models\Signage;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'signage_videos';

    protected $fillable = [
        'title', 'title_original', 'file', 'note', 'active'
    ];
}
