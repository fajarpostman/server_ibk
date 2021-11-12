@extends('layouts.app')
@section('title_page', 'Home')
@section('description_page', 'Welcome to IBK Display Signage Home')

@section('content')
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua">
                <i class="fa fa-map-pin"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text">@lang('backend.location')</span>
                <span class="info-box-number">{{ number_format($location) }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green">
                <i class="fa fa-code-fork"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text">Online @lang('backend.device')</span>
                <span class="info-box-number">{{ number_format($device_online) }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red">
                <i class="fa fa-ban"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text">Offline @lang('backend.device')</span>
                <span class="info-box-number">{{ number_format($device_offline) }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-blue">
                <i class="fa fa-file-video-o"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text">@lang('backend.video')</span>
                <span class="info-box-number">{{ number_format($video) }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-navy">
                <i class="fa fa-map-o"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text">@lang('backend.banner')</span>
                <span class="info-box-number">{{ number_format($banner) }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-orange">
                <i class="fa fa-commenting-o"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text">@lang('backend.running_text')</span>
                <span class="info-box-number">{{ number_format($running_text) }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-purple">
                <i class="fa fa-dollar"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text">@lang('backend.exchange_rate')</span>
                <span class="info-box-number">{{ number_format($exchange_rate) }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-olive">
                <i class="fa fa-bar-chart"></i>
            </span>

            <div class="info-box-content">
                <span class="info-box-text">@lang('backend.deposito')</span>
                <span class="info-box-number">{{ number_format($deposito) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
