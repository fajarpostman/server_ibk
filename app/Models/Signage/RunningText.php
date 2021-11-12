<?php

namespace App\Models\Signage;

use Illuminate\Database\Eloquent\Model;

use App\Models\Signage\Display;

class RunningText extends Model
{
    protected $table = 'signage_running_texts';

    protected $fillable = [
        'text'
    ];

    public function display()
    {
        return $this->belongsTo(Display::class, 'display_id');
    }
}
