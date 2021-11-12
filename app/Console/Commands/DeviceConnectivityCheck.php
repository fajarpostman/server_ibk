<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Master\Device;

class DeviceConnectivityCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'device:ping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cek koneksi internet semua device';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $devices = Device::select('id')->orderBy('id', 'ASC')->pluck('id');
        foreach ($devices as $value) {
            $device = Device::findOrFail($value);
            $online = $this->pinger($device->ip_address);
            $device->online = $online;
            $device->update();
        }

        $this->info('All devices has been checked!');
    }

    public function pinger($url)
    {
        if (strtolower(PHP_OS) == 'winnt'){
            $output = shell_exec('ping -n 1 ' . $url);
        } else {
            $output = shell_exec('ping -c 1 ' . $url);
        }

        if ((stripos($output, 'Request Time Out')) ||
            (stripos($output, 'Destination host unreachable')) ||
            (stripos($output, 'Time to live exceeded')) ||
            (stripos($output, '100% loss'))) {
            return false;
        } else {
            return true;
        }
    }
}
