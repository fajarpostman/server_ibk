<?php

namespace App\Http\Controllers\Signage;

use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use App\Models\Master\Location;
use App\Models\Signage\Display;
use App\Models\Signage\Video;

class VideoController extends Controller
{
    private $date_format = 'd-m-Y H:m';

    public function index()
    {
        return view('backend.signage.video.index');
    }

    public function data()
    {
        if (request()->ajax()) {
            $videos = Video::all();
            return Datatables::of($videos)->addColumn('action',
                '<a href="{{ route("signage.video.edit", [$id]) }}" type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i>  @lang("backend.edit")</a>
                    &nbsp;
                    <a href="{{ route("signage.video.delete", [$id]) }}" type="button" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> @lang("backend.delete")</a>'
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
            ->editColumn('created_at', function ($videos) {
                return date($this->date_format, strtotime($videos->created_at) );
            })->make(true);
        } else {
            return response('Forbidden', 403);
        }
    }

    public function create()
    {
        $branchs = Location::with('device')->get();

        return view('backend.signage.video.create', compact('branchs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'video' => 'required|mimes:mp4,x-flv,x-mpegURL,MP2T,3gpp,quicktime,x-msvideo,x-ms-wmv,avi',
            'note' => 'nullable',
            'start_date' => 'date_format:d-m-Y H:i|before_or_equal:end_date',
            'end_date' => 'date_format:d-m-Y H:i|after_or_equal:start_date',
            'always_showing' => '',
        ]);

        $file = $request->file('video');

        try {
            $video = new Video();
            $video->title = $request->title;
            $video->title_original = $file->getClientOriginalName();
            $video->file = Storage::url($file->store('public/upload/videos'));
            $video->start_date = Carbon::make($request->start_date);
            $video->end_date = Carbon::make($request->end_date);
            if (isset($request->always_showing)) {
                $video->always_showing = true;
            }
            $video->save();

            Display::where('display_type', Video::class)
                ->where('display_id', $video->id)
                ->delete();

            if (isset($request->device)) {
                foreach ($request->device as $device) {
                    $display = new Display();
                    $display->device_id = $device;
                    $display->display_type = Video::class;
                    $display->display_id = $video->id;
                    $display->save();
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e);
        }

        return redirect()->route('signage.video.index')->with('success', __('backend.video') . __('backend.updated'));
    }

    public function edit($id, Request $request)
    {
        $video = Video::findOrFail($id);
        $branchs = Location::with('device')->get();

        $display_checked = Display::select('device_id')->where('display_type', 'LIKE', '%Video%')->where('display_id', $id)->pluck('device_id')->toArray();

        return view('backend.signage.video.edit', compact('video', 'branchs', 'display_checked'));

    }

    public function update($id, Request $request)
    {
        $request->validate([
            'title' => 'required',
            'video' => 'mimes:mp4,x-flv,x-mpegURL,MP2T,3gpp,quicktime,x-msvideo,x-ms-wmv,avi',
            'note' => 'nullable',
            'start_date' => 'date_format:d-m-Y H:i|before_or_equal:end_date',
            'end_date' => 'date_format:d-m-Y H:i|after_or_equal:start_date',
            'always_showing' => '',
        ]);

        $file = $request->file('video');

        try {
            $video = Video::findOrFail($id);
            $video->title = $request->title;
            if ($file) {
                Storage::delete($video->file);
                $video->title_original = $file->getClientOriginalName();
                $video->file = Storage::url($file->store('public/upload/videos'));
            }
            $video->start_date = Carbon::make($request->start_date);
            $video->end_date = Carbon::make($request->end_date);
            if (isset($request->always_showing)) {
                $video->always_showing = true;
            }
            $video->update();

            Display::where('display_type', Video::class)
                ->where('display_id', $video->id)
                ->delete();

            if (isset($request->device)) {
                foreach ($request->device as $device) {
                    $display = new Display();
                    $display->device_id = $device;
                    $display->display_type = Video::class;
                    $display->display_id = $video->id;
                    $display->save();
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e);
        }

        return redirect()->route('signage.video.index')->with('success', __('backend.video') . __('backend.updated'));
    }

    public function delete($id)
    {
        $video = Video::findOrFail($id);
        Storage::delete($video->file);
        $video->delete();

        return redirect()->route('signage.video.index')->with('success', __('backend.video') . __('backend.deleted'));
    }
}
