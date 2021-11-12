<?php

namespace App\Models\Signage;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'signage_banners';

    protected $fillable = [
        'title', 'title_original', 'file', 'note', 'active'
    ];
}
