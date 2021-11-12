<?php

use App\Models\Master\Device;
use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $devices = [
            ['1', 'RASP101', '192.168.1.101', false, 'LOBI', 'TIDAK BERMASALAH'],
            ['1', 'RASP102', '192.168.1.102', false, 'LOBI', 'TIDAK BERMASALAH'],
            ['1', 'RASP103', '192.168.1.103', false, 'LOBI', 'TIDAK BERMASALAH'],
            ['2', 'RASP201', '192.168.1.104', false, 'TELLER', 'TIDAK BERMASALAH'],
            ['2', 'RASP202', '192.168.1.105', false, 'TELLER', 'TIDAK BERMASALAH'],
            ['2', 'RASP203', '192.168.1.106', false, 'TELLER', 'JARINGAN TIDAK KONEK'],
            ['3', 'RASP201', '192.168.1.104', false, 'TELLER', 'TIDAK BERMASALAH'],
            ['3', 'RASP202', '192.168.1.105', false, 'TELLER', 'TIDAK BERMASALAH'],
            ['3', 'RASP203', '192.168.1.106', false, 'TELLER', 'JARINGAN TIDAK KONEK'],
            ['4', 'RASP201', '192.168.1.104', false, 'TELLER', 'TIDAK BERMASALAH'],
            ['4', 'RASP202', '192.168.1.105', false, 'TELLER', 'TIDAK BERMASALAH'],
            ['4', 'RASP203', '192.168.1.106', false, 'TELLER', 'JARINGAN TIDAK KONEK'],
            ['5', 'RASP201', '192.168.1.104', false, 'TELLER', 'TIDAK BERMASALAH'],
            ['5', 'RASP202', '192.168.1.105', false, 'TELLER', 'TIDAK BERMASALAH'],
            ['5', 'RASP203', '192.168.1.106', false, 'TELLER', 'JARINGAN TIDAK KONEK'],
            ['6', 'RASP201', '192.168.1.104', false, 'TELLER', 'TIDAK BERMASALAH'],
            ['6', 'RASP202', '192.168.1.105', false, 'TELLER', 'TIDAK BERMASALAH'],
            ['6', 'RASP203', '192.168.1.106', false, 'TELLER', 'JARINGAN TIDAK KONEK'],
            ['7', 'RASP201', '192.168.1.104', false, 'TELLER', 'TIDAK BERMASALAH'],
            ['7', 'RASP202', '192.168.1.105', false, 'TELLER', 'TIDAK BERMASALAH'],
            ['7', 'RASP203', '192.168.1.106', false, 'TELLER', 'JARINGAN TIDAK KONEK'],
        ];
        foreach ($devices as $device) {
            Device::create([
                'location_id'   => $device[0],
                'device'        => $device[1],
                'ip_address'    => $device[2],
                'online'        => $device[3],
                'location'      => $device[4],
                'note'          => $device[5],
            ]);
        }
    }
}
