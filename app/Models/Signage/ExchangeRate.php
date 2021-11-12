<?php

namespace App\Models\Signage;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $table = 'signage_exchange_rates';

    protected $fillable = [
        'nomor', 'country', 'type', 'bank_buy', 'bank_sell'
    ];
}
