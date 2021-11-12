<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

use App\Models\User\User;
use App\Models\Master\Device;
use App\Models\Signage\Video;
use App\Models\Signage\Banner;
use App\Models\Master\Location;
use App\Models\Signage\Deposito;
use App\Models\Signage\RunningText;
use App\Models\Signage\ExchangeRate;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $location = Location::count();
        $device_online = Device::where('online', 1)->count();
        $device_offline = Device::where('online', 0)->count();
        $running_text = RunningText::count();
        $video = Video::count();
        $banner = Banner::count();
        $exchange_rate = ExchangeRate::count();
        $deposito = Deposito::count();

        return view('home', compact(
            'location', 'device_online', 'device_offline', 'running_text',
            'video', 'banner', 'exchange_rate', 'deposito'
        ));
    }

    public function language()
    {
        return view('backend.setting.language');
    }

    public function languageApply(Request $request)
    {
        App::setLocale($request->language);
        Session::put('applocale', $request->language);
        return redirect()->route('index')->with('success', __('backend.setting_page.language') . __('backend.updated'));
    }

    public function account()
    {
        $current_account = User::where('id', Auth::user()->id)->first();
        return view('backend.setting.account.edit', compact('current_account'));
    }

    public function accountApply(Request $request)
    {
        $request->validate([
            'username'  => 'nullable',
            'email'     => 'nullable|email',
            'password'  => 'confirmed',
        ]);

        $account = User::where('id', Auth::user()->id)->first();
        if ($request->username != null)
            $account->username = $request->username;
        if ($request->email != null)
            $account->email = $request->email;
        if ($request->password != null)
            $account->password = Hash::make($request->password);
        $account->update();

        return redirect()->route('index')->with('success', 'Account has been updated!');
    }
}
