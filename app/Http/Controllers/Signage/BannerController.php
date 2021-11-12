<?php

namespace App\Http\Controllers\Signage;

use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\Models\Master\Location;
use App\Models\Signage\Display;
use App\Models\Signage\Banner;

class BannerController extends Controller
{
    private $date_format = 'd-m-Y H:m';

    public function index()
    {
        return view('backend.signage.banner.index');
    }

    public function data()
    {
        if (request()->ajax()) {
            $banners = Banner::all();
            return Datatables::of($banners)->addColumn('action',
                '<a href="{{ route("signage.banner.edit", [$id]) }}" type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i>  @lang("backend.edit")</a>
                    &nbsp;
                    <a href="{{ route("signage.banner.delete", [$id]) }}" type="button" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> @lang("backend.delete")</a>'
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
            ->editColumn('created_at', function ($banners) {
                return date($this->date_format, strtotime($banners->created_at) );
            })->make(true);
        } else {
            return response('Forbidden', 403);
        }
    }

    public function create()
    {
        $branchs = Location::with('device')->get();

        return view('backend.signage.banner.create', compact('branchs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required|image',
            'note' => 'nullable',
            'start_date' => 'date_format:d-m-Y H:i|before_or_equal:end_date',
            'end_date' => 'date_format:d-m-Y H:i|after_or_equal:start_date',
            'always_showing' => '',
        ]);

        $file = $request->file('image');

        try {
            $banner = new Banner();
            $banner->title = $request->title;
            $banner->title_original = $file->getClientOriginalName();
            $banner->file = Storage::url($file->store('public/upload/banners'));
            $banner->start_date = Carbon::make($request->start_date);
            $banner->end_date = Carbon::make($request->end_date);
            if (isset($request->always_showing)) {
                $banner->always_showing = true;
            }
            $banner->save();

            Display::where('display_type', Banner::class)
                ->where('display_id', $banner->id)
                ->delete();

            if (isset($request->device)) {
                foreach ($request->device as $device) {
                    $display = new Display();
                    $display->device_id = $device;
                    $display->display_type = Banner::class;
                    $display->display_id = $banner->id;
                    $display->save();
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e);
        }

        return redirect()->route('signage.banner.index')->with('success', __('backend.banner') . __('backend.updated'));
    }

    public function edit($id, Request $request)
    {
        $banner = Banner::findOrFail($id);
        $branchs = Location::with('device')->get();

        $display_checked = Display::select('device_id')->where('display_type', 'LIKE', '%Banner%')->where('display_id', $id)->pluck('device_id')->toArray();

        // // return $display_checked;
        // $arraw = array(1,2,3);

        // // return $arraw;
        // if (in_array(16, $display_checked)) {
        //     return "true";
        // } else {
        //     return "false";
        // }

        // exit();
        return view('backend.signage.banner.edit', compact('banner', 'branchs', 'display_checked'));

    }

    public function update($id, Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'image',
            'note' => 'nullable',
            'start_date' => 'date_format:d-m-Y H:i|before_or_equal:end_date',
            'end_date' => 'date_format:d-m-Y H:i|after_or_equal:start_date',
            'always_showing' => '',
        ]);

        $file = $request->file('image');

        try {
            $banner = Banner::findOrFail($id);
            $banner->title = $request->title;
            if ($file) {
                Storage::delete($banner->file);
                $banner->title_original = $file->getClientOriginalName();
                $banner->file = Storage::url($file->store('public/upload/banners'));
            }
            $banner->start_date = Carbon::make($request->start_date);
            $banner->end_date = Carbon::make($request->end_date);
            if (isset($request->always_showing)) {
                $banner->always_showing = true;
            }
            $banner->update();

            Display::where('display_type', Banner::class)
                ->where('display_id', $banner->id)
                ->delete();

            if (isset($request->device)) {
                foreach ($request->device as $device) {
                    $display = new Display();
                    $display->device_id = $device;
                    $display->display_type = Banner::class;
                    $display->display_id = $banner->id;
                    $display->save();
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e);
        }

        return redirect()->route('signage.banner.index')->with('success', __('backend.banner') . __('backend.updated'));
    }

    public function delete($id)
    {
        $banner = Banner::findOrFail($id);
        Storage::delete($banner->file);
        $banner->delete();

        return redirect()->route('signage.banner.index')->with('success', __('backend.banner') . __('backend.deleted'));
    }
}
