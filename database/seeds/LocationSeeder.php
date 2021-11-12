<?php

use App\Models\Master\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = [
            ['IBKTGR 0001', 'IBK TANGERANG SUDIRMAN', 'JL. SUDIRMAN NO.1 TANGERANG SELATAN', 'TANGERANG'],
            ['IBKTGR 0002', 'IBK TANGERANG SERPONG', 'JL. PADAT KARYA NO.13 SERPONG UTARA', 'TANGERANG'],
            ['IBKTGR 0003', 'IBK JAKARTA SELATAN', 'JL. PADAT KARYA NO.13 SERPONG UTARA', 'JAKARTA'],
            ['IBKTGR 0004', 'IBK JAKARTA UTARA', 'JL. PADAT KARYA NO.13 SERPONG UTARA', 'JAKARTA'],
            ['IBKTGR 0005', 'IBK JAKARTA TIMUR', 'JL. PADAT KARYA NO.13 SERPONG UTARA', 'JAKARTA'],
            ['IBKTGR 0006', 'IBK JAKARTA BARAT', 'JL. PADAT KARYA NO.13 SERPONG UTARA', 'JAKARTA'],
            ['IBKTGR 0007', 'IBK JAKARTA SELATAN', 'JL. PADAT KARYA NO.13 SERPONG UTARA', 'JAKARTA']
        ];
        foreach ($locations as $location) {
            Location::create([
                'branch_code' => $location[0],
                'branch' => $location[1],
                'address' => $location[2],
                'city' => $location[3]
            ]);
        }
    }
}
