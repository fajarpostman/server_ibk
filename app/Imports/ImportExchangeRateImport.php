<?php

namespace App\Imports;

use App\Models\Signage\Exchangerate;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportExchangeRateImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ExchangeRate([
            //
            'country' => $row[0],
        ]);
    }
}
