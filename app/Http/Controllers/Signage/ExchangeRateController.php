<?php

namespace App\Http\Controllers\Signage;

use Storage;
use Datatables;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Exception;
use App\Models\Master\Device;
use App\Models\Master\Location;
use App\Models\Signage\Display;
use App\Models\Signage\ExchangeRate;
use App\Imports\ExchangeRateImport;



class ExchangeRateController extends Controller
{
    private $date_format = 'd-m-Y H:m';

    public function index()
    {
        return view('backend.signage.exchange_rate.index');
    }

    public function data()
    {
        if (request()->ajax()) {
            $exchange_rates = ExchangeRate::all();
            return Datatables::of($exchange_rates)->addColumn('action',
                '<a href="{{ route("signage.exchange-rate.edit", [$id]) }}" type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i>  @lang("backend.edit")</a>
                    &nbsp;
                    <a href="{{ route("signage.exchange-rate.delete", [$id]) }}" type="button" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> @lang("backend.delete")</a>'
            )->editColumn('start_date', function ($running_text) {
                return date($this->date_format, strtotime($running_text->start_date) );
            })->editColumn('end_date', function ($running_text) {
                return date($this->date_format, strtotime($running_text->end_date) );
            })
            ->editColumn('updated_at', function ($exchange_rates) {
                return date($this->date_format, strtotime($exchange_rates->updated_at) );
            })->make(true);
        } else {
            return response('Forbidden', 403);
        }
    }

    public function create()
    {
        $branchs = Location::with('device')->get();

        return view('backend.signage.exchange_rate.create', compact('branchs'));
    }

    public function import()
    {
        $branchs = Location::with('device')->get();

        return view('backend.signage.exchange_rate.import', compact('branchs'));
    }

    public function storeData(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,txt'
        ]);

        // $date = new DateTime;
        $exchange_rate = new ExchangeRate();
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $nama_file = date('Y-m-d').$file->getClientOriginalName();
            $destinasi = 'public/kurs/';
            $file->move($destinasi, $nama_file);
            Excel::import(new ExchangeRateImport, public_path('/public/kurs/' . $nama_file));

            Display::where('display_type', ExchangeRate::class)
                ->where('display_id', $exchange_rate->id)
                ->delete();

            if (isset($request->device)) {
                foreach ($request->device as $device) {
                    $display = new Display();
                    $display->device_id = $device;
                    $display->display_type = ExchangeRate::class;
                    $display->display_id = $exchange_rate->id;
                    $display->save();
                }
            }
        }

        return redirect()->route('signage.exchange-rate.index')->with('success', __('backend.exchange_rate') . __('backend.added'));
    }

    public function store(Request $request)
    {
        if ($request->bank_buy != NULL || $request->bank_sell != NULL) {
            $request->validate([
                'country' => 'required',
                'type' => 'required',
                'bank_buy' => 'nullable',
                'bank_sell' => 'nullable',
                'start_date' => 'date_format:d-m-Y H:i|before_or_equal:end_date',
                'end_date' => 'date_format:d-m-Y H:i|after_or_equal:start_date',
                'always_showing' => '',
            ]);

            try {
                $exchange_rate = new ExchangeRate();
                $exchange_rate->country = $request->country;
                $exchange_rate->type = $request->type;
                $exchange_rate->bank_buy = $request->bank_buy;
                $exchange_rate->bank_sell = $request->bank_sell;
                $exchange_rate->start_date = Carbon::make($request->start_date);
                $exchange_rate->end_date = Carbon::make($request->end_date);
                if (isset($request->always_showing)) {
                    $exchange_rate->always_showing = true;
                }
                $exchange_rate->save();

                Display::where('display_type', ExchangeRate::class)
                    ->where('display_id', $exchange_rate->id)
                    ->delete();

                if (isset($request->device)) {
                    foreach ($request->device as $device) {
                        $display = new Display();
                        $display->device_id = $device;
                        $display->display_type = ExchangeRate::class;
                        $display->display_id = $exchange_rate->id;
                        $display->save();
                    }
                }
            } catch (Exception $e) {
                return redirect()->back()->with('error', $e);
            }
        } else if ($request->import_exchange != NULL) {
            $import = $request->file('import_exchange');
            Storage::disk('local')->putFileAs('public/upload/exchange_rates/', $import, 'Kurs.csv');
            $filename = storage_path('app/public/upload/exchange_rates/Kurs.csv');
            $file = fopen($filename, "r");
            while (($updatedd = fgetcsv($file)) !== FALSE) {
                if ($updatedd[0] == 'WIB') {
                    $waktu_update = $updatedd;
                }
            }
            fclose($file);
            while (($line = fgetcsv($file)) !== FALSE) {
                try {
                    if ($line[0] != 'WIB') {
                        $exchange_rate = new ExchangeRate();
                        $remove_illegal_char = preg_replace("/[^a-zA-Z0-9]/", "", $line[0]);
                        $exchange_rate->country = $remove_illegal_char;
                        if (is_string($line[1])) {
                            $exchange_rate->type = $line[1];
                        } else {
                            $exchange_rate = 'NULL';
                        }
                        if (is_numeric($line[2])) {
                            $exchange_rate->bank_buy = $line[2];
                        } else {
                            $exchange_rate->bank_buy = '0';
                        }
                        if (is_numeric($line[3])) {
                            $exchange_rate->bank_sell = $line[3];
                        } else {
                            $exchange_rate->bank_sell = '0';
                        }
                        $exchange_rate->always_showing = true;
                        $exchange_rate->start_date = Carbon::parse('yesterday');
                        $exchange_rate->end_date = Carbon::parse('5 years');
                        $exchange_rate->updated_at = Carbon::parse($waktu_update[2] . ' ' . $waktu_update[1]);
                        $exchange_rate->save();
                    } else {
                        $remove_illegal_char = preg_replace("/[^a-zA-Z0-9]/", "", $line[0]);
                        $exchange_rate = ExchangeRate::where('country', $remove_illegal_char)->first();
                        if (isset($exchange_rate)) {
                            if (is_string($line[1])) {
                                $exchange_rate->type = $line[1];
                            } else {
                                $exchange_rate = 'NULL';
                            }
                            if (is_numeric($line[2])) {
                                $exchange_rate->bank_buy = $line[2];
                            } else {
                                $exchange_rate->bank_buy = '0';
                            }
                            if (is_numeric($line[3])) {
                                $exchange_rate->bank_sell = $line[3];
                            } else {
                                $exchange_rate->bank_sell = '0';
                            }
                            $exchange_rate->always_showing = true;
                            $exchange_rate->start_date = Carbon::parse('yesterday');
                            $exchange_rate->end_date = Carbon::parse('5 years');
                            $exchange_rate->updated_at = Carbon::parse($waktu_update[2] . ' ' . $waktu_update[1]);
                            $exchange_rate->update();
                        }

                        Display::where('display_type', ExchangeRate::class)
                        ->where('display_id', $exchange_rate->id)
                        ->delete();

                        if (isset($request->device)) {
                            foreach($request->device as $device) {
                                $display = new Display();
                                $display->device_id = $device;
                                $display->display_type = ExchangeRate::class;
                                $display->display_id = $exchange_rate->id;
                                $display->save();
                            }
                        }
                    }
                } catch(\Expection $e) {
                    return redirect()->back()->with('error', $e);
                }
            }

        } else {
            $request->validate([
                'import_exchange' => 'required',
                'country' => 'required',
                'type' => 'required',
                'bank_buy' => 'nullable',
                'bank_sell' => 'nullable',
                'start_date' => 'date_format:d-m-Y H:i|before_or_equal:end_date',
                'end_date' => 'date_format:d-m-Y H:i|after_or_equal:start_date',
                'always_showing' => '',
            ]);
        }

        return redirect()->route('signage.exchange-rate.index')->with('success', __('backend.exchange_rate') . __('backend.added'));
    }

    public function edit($id, Request $request)
    {
        $exchange_rate = ExchangeRate::findOrFail($id);
        $branchs = Location::with('device')->get();

        $display_checked = Display::select('device_id')->where('display_type', 'LIKE', '%ExchangeRate%')->where('display_id', $id)->pluck('device_id')->toArray();

        return view('backend.signage.exchange_rate.edit', compact('exchange_rate', 'branchs', 'display_checked'));

    }

    public function update($id, Request $request)
    {
        $request->validate([
            'country' => 'required',
            'type' => 'required',
            'bank_buy' => 'nullable',
            'bank_sell' => 'nullable',
            'start_date' => 'date_format:d-m-Y H:i|before_or_equal:end_date',
            'end_date' => 'date_format:d-m-Y H:i|after_or_equal:start_date',
            'always_showing' => '',
        ]);

        try {
            $exchange_rate = ExchangeRate::findOrFail($id);
            $exchange_rate->country = $request->country;
            $exchange_rate->type = $request->type;
            $exchange_rate->bank_buy = $request->bank_buy;
            $exchange_rate->bank_sell = $request->bank_sell;
            $exchange_rate->start_date = Carbon::make($request->start_date);
            $exchange_rate->end_date = Carbon::make($request->end_date);
            if (isset($request->always_showing)) {
                $exchange_rate->always_showing = true;
            }
            $exchange_rate->update();

            Display::where('display_type', ExchangeRate::class)
                ->where('display_id', $exchange_rate->id)
                ->delete();

            if (isset($request->device)) {
                foreach ($request->device as $device) {
                    $display = new Display();
                    $display->device_id = $device;
                    $display->display_type = ExchangeRate::class;
                    $display->display_id = $exchange_rate->id;
                    $display->save();
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e);
        }

        return redirect()->route('signage.exchange-rate.index')->with('success', __('backend.exchange_rate') . __('backend.updated'));
    }

    public function delete($id)
    {
        $exchange_rate = ExchangeRate::findOrFail($id);
        $exchange_rate->delete();

        return redirect()->route('signage.exchange-rate.index')->with('success', __('backend.exchange_rate') . __('backend.deleted'));
    }
}
