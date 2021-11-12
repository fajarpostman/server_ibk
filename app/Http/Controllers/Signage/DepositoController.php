<?php

namespace App\Http\Controllers\Signage;

use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

use App\Models\Master\Location;
use App\Models\Signage\Display;
use App\Models\Signage\Deposito;

class DepositoController extends Controller
{
    private $date_format = 'd-m-Y H:m';

    public function index()
    {
        return view('backend.signage.deposito.index');
    }

    public function data()
    {
        if (request()->ajax()) {
            $depositos = Deposito::all();
            return Datatables::of($depositos)->addColumn('action',
                '<a href="{{ route("signage.deposito.edit", [$id]) }}" type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i>  @lang("backend.edit")</a>
                    &nbsp;
                    <a href="{{ route("signage.deposito.delete", [$id]) }}" type="button" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> @lang("backend.delete")</a>'
            )->editColumn('start_date', function ($running_text) {
                return date($this->date_format, strtotime($running_text->start_date) );
            })->editColumn('end_date', function ($running_text) {
                return date($this->date_format, strtotime($running_text->end_date) );
            })
            // ->editColumn('always_showing', function ($running_text) {
            //     if ($running_text->always_showing == 1) {
            //         return "Yes";
            //     } else {
            //         return "No";
            //     }
            // })
            ->editColumn('created_at', function ($depositos) {
                return date($this->date_format, strtotime($depositos->created_at) );
            })->make(true);
        } else {
            return response('Forbidden', 403);
        }
    }

    public function create()
    {
        $branchs = Location::with('device')->get();

        return view('backend.signage.deposito.create', compact('branchs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tenor' => 'required',
            'interest' => 'nullable',
            'start_date' => 'date_format:d-m-Y H:i|before_or_equal:end_date',
            'end_date' => 'date_format:d-m-Y H:i|after_or_equal:start_date',
            'always_showing' => '',
        ]);

        try {
            $deposito = new Deposito();
            $deposito->tenor = $request->tenor;
            $deposito->interest = $request->interest;
            $deposito->start_date = Carbon::make($request->start_date);
            $deposito->end_date = Carbon::make($request->end_date);
            if (isset($request->always_showing)) {
                $deposito->always_showing = true;
            }
            $deposito->save();

            Display::where('display_type', Deposito::class)
                ->where('display_id', $deposito->id)
                ->delete();

            if (isset($request->device)) {
                foreach ($request->device as $device) {
                    $display = new Display();
                    $display->device_id = $device;
                    $display->display_type = Deposito::class;
                    $display->display_id = $deposito->id;
                    $display->save();
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e);
        }

        return redirect()->route('signage.deposito.index')->with('success', __('backend.deposito') . __('backend.updated'));
    }

    public function edit($id, Request $request)
    {
        $deposito = Deposito::findOrFail($id);
        $branchs = Location::with('device')->get();

        $display_checked = Display::select('device_id')->where('display_type', 'LIKE', '%Deposito%')->where('display_id', $id)->pluck('device_id')->toArray();

        return view('backend.signage.deposito.edit', compact('deposito', 'branchs', 'display_checked'));

    }

    public function update($id, Request $request)
    {
        $request->validate([
            'tenor' => 'required',
            'interest' => 'nullable',
            'start_date' => 'date_format:d-m-Y H:i|before_or_equal:end_date',
            'end_date' => 'date_format:d-m-Y H:i|after_or_equal:start_date',
            'always_showing' => '',
        ]);

        try {
            $deposito = Deposito::findOrFail($id);
            $deposito->tenor = $request->tenor;
            $deposito->interest = $request->interest;
            $deposito->start_date = Carbon::make($request->start_date);
            $deposito->end_date = Carbon::make($request->end_date);
            if (isset($request->always_showing)) {
                $deposito->always_showing = true;
            }
            $deposito->update();

            Display::where('display_type', Deposito::class)
                ->where('display_id', $deposito->id)
                ->delete();

            if (isset($request->device)) {
                foreach ($request->device as $device) {
                    $display = new Display();
                    $display->device_id = $device;
                    $display->display_type = Deposito::class;
                    $display->display_id = $deposito->id;
                    $display->save();
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e);
        }

        return redirect()->route('signage.deposito.index')->with('success', __('backend.deposito') . __('backend.updated'));
    }

    public function delete($id)
    {
        $deposito = Deposito::findOrFail($id);
        $deposito->delete();

        return redirect()->route('signage.deposito.index')->with('success', __('backend.deposito') . __('backend.deleted'));
    }
}
