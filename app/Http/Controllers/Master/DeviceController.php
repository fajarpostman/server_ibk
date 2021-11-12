<?php

namespace App\Http\Controllers\Master;

use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Master\Device;
use App\Models\Master\Location;

class DeviceController extends Controller
{
    private $date_format = 'd-m-Y h:m';

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

    public function ping($id)
    {
        $device = Device::findOrFail($id);
        $online = $this->pinger($device->ip_address);
        $device->online = $online;
        $device->update();

        if ($online == true) {
            return redirect()->route('master.device.index')->with('success', 'Device '. $device->device .' connected.');
        } else {
            return redirect()->route('master.device.index')->with('danger', 'Device '. $device->device .' not connected, please check connection.');
        }

    }

    public function index()
    {
        return view('backend.master.device.index');
    }

    public function data()
    {
        if (request()->ajax()) {
            $devices = Device::all();
            return Datatables::of($devices)->addColumn('action',
                '<a href="{{ route("master.device.pinger", [$id]) }}" type="button" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-signal"></i>  Ping</a>
                    &nbsp;
                    <a href="{{ route("master.device.edit", [$id]) }}" type="button" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i>  @lang("backend.edit")</a>
                    &nbsp;
                    <a href="{{ route("master.device.delete", [$id]) }}" type="button" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> @lang("backend.delete")</a>
                    &nbsp;
                      <div class="btn-group">
                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                          <li><a href="#!" class="btn-sync-data" data-id="{{$id}}">Sync Data</a></li>
                          <li><a href="#!" class="btn-sync-file" data-id="{{$id}}">Sync File</a></li>
                          <li><a href="#!" class="btn-sync-refresh" data-id="{{$id}}">Refresh Device</a></li>
                        </ul>
                      </div>'
            )->editColumn('location_id', function ($devices) {
                return $devices->branch->branch_code;
            })->editColumn('online', function ($devices) {
                if ($devices->online == true) {
                    return '<span class="badge bg-green">Online</span>';
                } else {
                    return '<span class="badge bg-red">Offline</span>';
                }
            })->editColumn('created_at', function ($devices) {
                return date($this->date_format, strtotime($devices->created_at) );
            })->escapeColumns([])->make(true);
        } else {
            return response('Forbidden', 403);
        }
    }

    public function create()
    {
        $branch = Location::pluck('branch_code', 'id');
        return view('backend.master.device.create', compact('branch'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'branch' => 'required|integer',
            'device' => 'required',
            'ip_address' => 'required|ip',
            'location' => 'required',
            'note' => 'nullable',
        ]);

        try {
            $device = new Device();
            $device->location_id = $request->branch;
            $device->device = $request->device;
            $device->ip_address = $request->ip_address;
            $online = $this->pinger($device->ip_address);
            $device->online = $online;
            $device->location = $request->location;
            $device->note = $request->note;
            $device->save();

        } catch (Exception $e) {
            return redirect()->back()->with('error', $e);
        }

        return redirect()->route('master.device.index')->with('success', __('backend.device') . __('backend.added'));
    }

    public function edit($id, Request $request)
    {
        $device = Device::findOrFail($id);
        $branch = Location::pluck('branch_code', 'id');

        return view('backend.master.device.edit', compact('device', 'branch'));
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'branch' => 'required|integer',
            'device' => 'required',
            'ip_address' => 'required|ip',
            'location' => 'required',
            'note' => 'nullable',
        ]);

        try {
            $device = Device::findOrFail($id);
            $device->location_id = $request->branch;
            $device->device = $request->device;
            $device->ip_address = $request->ip_address;
            $online = $this->pinger($device->ip_address);
            $device->online = $online;
            $device->location = $request->location;
            $device->note = $request->note;
            $device->update();

        } catch (Exception $e) {
            return redirect()->back()->with('error', $e);
        }

        return redirect()->route('master.device.index')->with('success', __('backend.device') . __('backend.updated'));
    }

    public function delete($id)
    {
        $device = Device::findOrFail($id)->delete();

        return redirect()->route('master.device.index')->with('success', __('backend.device') . __('backend.deleted'));
    }

    public function syncData($id)
    {
        $device = Device::findOrFail($id);

        if ($this->pinger($device->ip_address) == true) {

            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $device->ip_address . '/sync.php');

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
            } catch (\Exception $e) {
                return '<div class="alert alert-danger alert-dismissable" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    Error: '. $e .'
                </div>';
            }

            return '<div class="alert alert-success alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            Data of device '. $device->device .' connected.
        </div>';

        } else {

            return '<div class="alert alert-danger alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            Data of device '. $device->device .' not connected.
        </div>';

        }
    }

    public function syncFile($id)
    {
        $device = Device::findOrFail($id);

        if ($this->pinger($device->ip_address) == true) {

            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $device->ip_address . '/sync_file.php');

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
            } catch (\Exception $e) {
                return '<div class="alert alert-danger alert-dismissable" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    Error: '. $e .'
                </div>';
            }

            return '<div class="alert alert-success alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            File of device '. $device->device .' connected.
        </div>';

        } else {

            return '<div class="alert alert-danger alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            File of device '. $device->device .' not connected.
        </div>';

        }
    }

    public function refresh($id)
    {
        $device = Device::findOrFail($id);

        if ($this->pinger($device->ip_address) == true) {

            $refresh_devices = DB::table('refresh_devices')->where('device_id', $id)->first();

            if (!empty($refresh_devices)){
                DB::table('refresh_devices')->where('device_id', $id)->delete();
            }

            $insert_refresh = DB::table('refresh_devices')->insert([
                'device_id' => $id
            ]);

            return '<div class="alert alert-success alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            Device '. $device->device .' will be refresh immediately.
        </div>';

        } else {

            return '<div class="alert alert-danger alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            File of device '. $device->device .' not connected.
        </div>';

        }
    }

    public function refreshCek($id)
    {
        // if (request()->ajax()) {
            $refresh_devices = DB::table('refresh_devices')->where('device_id', $id)->first();

            if (!empty($refresh_devices)){
                DB::table('refresh_devices')->where('device_id', $id)->delete();
                return json_encode(array('status'=> 'refresh'), 200);
            } else {
                return json_encode(array('status'=> 'active'), 200);
            }
        // }
    }
}
