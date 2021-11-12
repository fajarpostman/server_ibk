<?php

namespace App\Models\Master;

use App\Models\Master\Location;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'devices';

    protected $fillable = [
        'device', 'ip_address', 'online', 'location', 'note'
    ];

    public function branch()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
