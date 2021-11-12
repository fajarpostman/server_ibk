<?php

namespace App\Http\Controllers\Signage;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DisplayController extends Controller
{
    /**
     * QUERY NYA
     *
     * SELECT * FROM `signage_display` join signage_videos on signage_display.display_id = signage_videos.id where display_type like '%Video%' and signage_display.device_id = 1
     * @var string
     */
    public function index($device)
    {
        $video = DB::table('signage_display')
            ->join('signage_videos', 'signage_display.display_id', 'signage_videos.id')
            ->where('signage_display.display_type', 'like', '%Video%')
            ->where('device_id', $device)
            ->select(['signage_videos.id', 'file', 'signage_videos.start_date', 'signage_videos.end_date', 'signage_videos.always_showing'])
            ->get();

        $running_text = DB::table('signage_display')
            ->join('signage_running_texts', 'signage_display.display_id', 'signage_running_texts.id')
            ->where('signage_display.display_type', 'like', '%RunningText%')
            ->where('device_id', $device)
            ->select(['signage_running_texts.id', 'text', 'signage_running_texts.start_date', 'signage_running_texts.end_date', 'signage_running_texts.always_showing'])
            ->get();

        $banner = DB::table('signage_display')
            ->join('signage_banners', 'signage_display.display_id', 'signage_banners.id')
            ->where('signage_display.display_type', 'like', '%Banner%')
            ->where('device_id', $device)
            ->select(['signage_banners.id', 'file', 'signage_banners.start_date', 'signage_banners.end_date', 'signage_banners.always_showing'])
            ->get();

        $exchange_rate = DB::table('signage_display')
            ->join('signage_exchange_rates', 'signage_display.display_id', 'signage_exchange_rates.id')
            ->where('signage_display.display_type', 'like', '%ExchangeRate%')
            ->where('device_id', $device)
            ->select(['signage_exchange_rates.id', 'country', 'type','bank_buy', 'bank_sell', 'signage_exchange_rates.start_date', 'signage_exchange_rates.end_date', 'signage_exchange_rates.always_showing', 'signage_exchange_rates.updated_at'])
            ->get();

        $deposito = DB::table('signage_display')
            ->join('signage_depositos', 'signage_display.display_id', 'signage_depositos.id')
            ->where('signage_display.display_type', 'like', '%Deposito%')
            ->where('device_id', $device)
            ->select(['signage_depositos.id', 'tenor', 'interest', 'signage_depositos.start_date', 'signage_depositos.end_date', 'signage_depositos.always_showing'])
            ->get();

        return [
            'video' => $video,
            'running_text' => $running_text,
            'banner' => $banner,
            'exchange_rate' => $exchange_rate,
            'deposito' => $deposito
        ];
    }
}
