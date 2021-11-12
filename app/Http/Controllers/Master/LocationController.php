<?php

namespace App\Http\Controllers\Master;

use Datatables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Master\Location;

class LocationController extends Controller
{
    private $date_format = 'd-m-Y h:m';

    public function index()
    {
        return view('backend.master.location.index');
    }

    public function data()
    {
        if (request()->ajax()) {
            $locations = Location::all();
            return Datatables::of($locations)->addColumn('action',
                '<a href="{{ route("master.location.edit", [$id]) }}" type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i>  @lang("backend.edit")</a>
                    &nbsp;
                    <a href="{{ route("master.location.delete", [$id]) }}" type="button" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> @lang("backend.delete")</a>'
            )->editColumn('created_at', function ($locations) {
                return date($this->date_format, strtotime($locations->created_at) );
            })->make(true);
        } else {
            return response('Forbidden', 403);
        }
    }

    public function create()
    {
        return view('backend.master.location.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'branch_code' => 'required',
            'branch' => 'required',
            'address' => 'required',
            'city' => 'required',
        ]);

        try {
            $location = new Location();
            $location->branch_code = $request->branch_code;
            $location->branch = $request->branch;
            $location->city = $request->city;
            $location->address = $request->address;
            $location->save();
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e);
        }

        return redirect()->route('master.location.index')->with('success', __('backend.location') . __('backend.added'));
    }

    public function edit($id, Request $request)
    {
        $location = Location::findOrFail($id);

        // return $location;

        return view('backend.master.location.edit', compact('location'));
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'branch_code' => 'required',
            'branch' => 'required',
            'address' => 'required',
            'city' => 'required',
        ]);

        try {
            $location = Location::findOrFail($id);
            $location->branch_code = $request->branch_code;
            $location->branch = $request->branch;
            $location->city = $request->city;
            $location->address = $request->address;
            $location->update();
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e);
        }

        return redirect()->route('master.location.index')->with('success', __('backend.location') . __('backend.updated'));
    }

    public function delete($id)
    {
        $location = Location::findOrFail($id)->delete();

        return redirect()->route('master.location.index')->with('success', __('backend.location') . __('backend.deleted'));
    }
}
