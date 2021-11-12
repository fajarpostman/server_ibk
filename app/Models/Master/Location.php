<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

use App\Models\Master\Device;

class Location extends Model
{
    protected $table = 'locations';

    protected $fillable = [
        'branch_code', 'branch', 'address', 'city'
    ];

    public function device()
    {
        return $this->hasMany(Device::class, 'location_id');
    }
}
