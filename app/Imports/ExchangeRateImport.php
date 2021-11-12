<?php

namespace App\Imports;

use App\Models\Signage\Exchangerate;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
// use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\ToCollection;


class ExchangeRateImport implements OnEachRow
{
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        if (!isset($row[0])) {
            return null;
        }

        ExchangeRate::updateOrCreate(
                ['nomor' => $row[0]],
                [
                    'nomor' => $row[0],
                    'country' => preg_replace("/[^a-zA-Z0-9]/", "", $row[1]),
                    'type' => $row[2],
                    'bank_buy' => $row[3],
                    'bank_sell' => $row[4],
                    'always_showing' => $row[5],
                    'start_date' => $row[6],
                    'end_date' => $row[7],
                    'updated_at' => $row[8]
                ],
            );
    }
}