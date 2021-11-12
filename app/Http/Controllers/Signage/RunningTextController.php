<?php

namespace App\Http\Controllers\Signage;

use Datatables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Master\Location;
use App\Models\Signage\Display;
use App\Models\Signage\RunningText;
use Carbon\Carbon;

class RunningTextController extends Controller
{
    private $date_format = 'd-m-Y H:m';

    public function index()
    {
        return view('backend.signage.running_text.index');
    }

    public function data()
    {
        if (request()->ajax()) {
            $running_text = RunningText::all();
            return Datatables::of($running_text)->addColumn('action',
                '<a href="{{ route("signage.running-text.edit", [$id]) }}" type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i>  @lang("backend.edit")</a>
                    &nbsp;
                    <a href="{{ route("signage.running-text.delete", [$id]) }}" type="button" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> @lang("backend.delete")</a>'
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
            ->editColumn('created_at', function ($running_text) {
                return date($this->date_format, strtotime($running_text->created_at) );
            })->make(true);
        } else {
            return response('Forbidden', 403);
        }
    }

    public function create()
    {
        $branchs = Location::with('device')->get();

        return view('backend.signage.running_text.create', compact('branchs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required',
            'start_date' => 'date_format:d-m-Y H:i|before_or_equal:end_date',
            'end_date' => 'date_format:d-m-Y H:i|after_or_equal:start_date',
            'always_showing' => '',
        ]);

        try {
            $running_text = new RunningText();
            $running_text->text = $request->text;
            $running_text->start_date = Carbon::make($request->start_date);
            $running_text->end_date = Carbon::make($request->end_date);
            if (isset($request->always_showing)) {
                $running_text->always_showing = true;
            }
            $running_text->save();

            Display::where('display_type', RunningText::class)
                ->where('display_id', $running_text->id)
                ->delete();

            if (isset($request->device)) {
                foreach ($request->device as $device) {
                    $display = new Display();
                    $display->device_id = $device;
                    $display->display_type = RunningText::class;
                    $display->display_id = $running_text->id;
                    $display->save();
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e);
        }

        return redirect()->route('signage.running-text.index')->with('success', __('backend.running_text') . __('backend.updated'));
    }

    public function edit($id, Request $request)
    {
        $running_text = RunningText::findOrFail($id);
        $branchs = Location::with('device')->get();

        $display_checked = Display::select('device_id')->where('display_type', 'LIKE', '%RunningText%')->where('display_id', $id)->pluck('device_id')->toArray();

        return view('backend.signage.running_text.edit', compact('running_text', 'branchs', 'display_checked'));

    }

    public function update($id, Request $request)
    {
        $request->validate([
            'text' => 'required',
            'start_date' => 'date_format:d-m-Y H:i|before_or_equal:end_date',
            'end_date' => 'date_format:d-m-Y H:i|after_or_equal:start_date',
            'always_showing' => '',
        ]);

        try {
            $running_text = RunningText::findOrFail($id);
            $running_text->text = $request->text;
            $running_text->start_date = Carbon::make($request->start_date);
            $running_text->end_date = Carbon::make($request->end_date);
            if (isset($request->always_showing)) {
                $running_text->always_showing = true;
            }
            $running_text->update();

            Display::where('display_type', RunningText::class)
                ->where('display_id', $running_text->id)
                ->delete();

            if (isset($request->device)) {
                foreach ($request->device as $device) {
                    $display = new Display();
                    $display->device_id = $device;
                    $display->display_type = RunningText::class;
                    $display->display_id = $running_text->id;
                    $display->save();
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e);
        }

        return redirect()->route('signage.running-text.index')->with('success', __('backend.running_text') . __('backend.updated'));
    }

    public function delete($id)
    {
        $running_text = RunningText::findOrFail($id)->delete();

        return redirect()->route('signage.running-text.index')->with('success', __('backend.running_text') . __('backend.deleted'));
    }
}
