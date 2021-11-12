<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use File;

use App\Models\Master\Device;
use App\Models\Signage\Display;
use App\Models\Signage\ExchangeRate;

class SchedulingImportKurs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:kurs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data kurs ke server';

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
         $devices = Device::pluck('id');
         $csvFile = public_path('assets/exchange/');
         $check_file_exist = scandir($csvFile);
         array_splice($check_file_exist, 0, 2);
         rsort($check_file_exist);
         if ($check_file_exist != null) {
             foreach ($check_file_exist as $filename) {
                 $this->info('Importing ' . $filename);
                 $array = explode('.', $filename);
                 $extension = end($array);
                 if ($extension == 'csv') {
                     $file = fopen(public_path('assets/exchange/') . $filename, "r");
                     while (($updatedd = fgetcsv($file)) !== FALSE) {
                         if ($updatedd[0] == 'WIB') {
                             $waktu_update = $updatedd;
                         }
                     }
                     fclose($file);

                     $file = fopen(public_path('assets/exchange/') . $filename, "r");
                     while (($line = fgetcsv($file, 1000, ",")) !== FALSE) {
                         var_dump($line, '<=== BATAS ===>');
                         try {
                             if ($line[0] != 'WIB') {
                                $exchange_rate = ExchangeRate::where('nomor', $line[0])->first();
                                if (isset($exchange_rate)) {
                                    $exchange_rate->country = $line[1];
                                    if (is_string($line[2])) {
                                        $exchange_rate->type = $line[2];
                                    } else {
                                        $exchange_rate->type = 'NULL';
                                    }
                                    if (is_numeric($line[3])) {
                                        $exchange_rate->bank_buy = $line[3];
                                    } else {
                                        $exchange_rate->bank_buy = '0';
                                    }
                                    if (is_numeric($line[4])) {
                                        $exchange_rate->bank_sell = $line[4];
                                    } else {
                                        $exchange_rate->bank_sell = '0';
                                    }
                                    $exchange_rate->always_showing = true;
                                    $exchange_rate->end_date = Carbon::parse('5 years');
                                    $exchange_rate->updated_at = Carbon::parse($waktu_update[2] . ' ' . $waktu_update[1]);
                                    $exchange_rate->update();
                                } else {
                                    $exchange_rate = new ExchangeRate();
                                    $exchange_rate->nomor = $line[0];
                                    $exchange_rate->country = $line[1];
                                    if (is_string($line[2])) {
                                        $exchange_rate->type = $line[2];
                                    } else {
                                        $exchange_rate->type = 'NULL';
                                    }
                                    if (is_numeric($line[3])) {
                                        $exchange_rate->bank_buy = $line[3];
                                    } else {
                                        $exchange_rate->bank_buy = '0';
                                    }
                                    if (is_numeric($line[4])) {
                                        $exchange_rate->bank_sell = $line[4];
                                    } else {
                                        $exchange_rate->bank_sell = '0';
                                    }
                                    $exchange_rate->always_showing = true;
                                    $exchange_rate->end_date = Carbon::parse('5 years');
                                    $exchange_rate->updated_at = Carbon::parse($waktu_update[2] . ' ' . $waktu_update[1]);
                                    $exchange_rate->save();
                                }

                                Display::where('display_type', ExchangeRate::class)
                                ->where('display_id', $exchange_rate->id)
                                    ->delete();

                                foreach ($devices as $device) {
                                    $display = new Display();
                                    $display->device_id = $device;
                                    $display->display_type = ExchangeRate::class;
                                    $display->display_id = $exchange_rate->id;
                                    $display->save();
                                }
                             }

                         } catch (\Exception $e) {
                            return redirect()->back()->with('error', $e);
                         }
                     }

                     fclose($file);
                 }

                 $time = Carbon::now()->format('dmYhis');
                //  $time->toDateTimeString();

                 File::move(public_path('assets/exchange/'.$filename), public_path('kurs/exchange/'.$time.$filename));

                // $file->move(public_path('kurs/exchange/'), $file->getClientOriginalName());
                // unlink(public_path('assets/exchange/'.$filename));
             }

             $this->info('Exchange Rate Succesfully imported!');
         } else {
             $this->info('Nothing to import!');
             $this->info('Put Your .CSV file to public/assets/exchange');
         }
     }
}