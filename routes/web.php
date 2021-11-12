<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::middleware('auth')->group( function () {

    Route::get('', 'HomeController@index')->name('index');
    Route::get('/setting/account', 'HomeController@account')->name('setting.account');
    Route::post('/setting/account', 'HomeController@accountApply')->name('setting.account.apply');
    Route::get('/setting/language', 'HomeController@language')->name('setting.language');
    Route::post('/setting/language', 'HomeController@languageApply')->name('setting.language.apply');

    Route::name('signage.')->prefix('signage')->namespace('Signage')->group( function () {

        Route::name('running-text.')->prefix('running-text')->group( function () {
            Route::get('', 'RunningTextController@index')->name('index');
            Route::get('data', 'RunningTextController@data')->name('data');
            Route::get('create', 'RunningTextController@create')->name('create');
            Route::post('store', 'RunningTextController@store')->name('store');
            Route::get('edit/{id}', 'RunningTextController@edit')->name('edit');
            Route::patch('update/{id}', 'RunningTextController@update')->name('update');
            Route::get('delete/{id}', 'RunningTextController@delete')->name('delete');
        });

        Route::name('video.')->prefix('video')->group( function () {
            Route::get('', 'VideoController@index')->name('index');
            Route::get('data', 'VideoController@data')->name('data');
            Route::get('create', 'VideoController@create')->name('create');
            Route::post('store', 'VideoController@store')->name('store');
            Route::get('edit/{id}', 'VideoController@edit')->name('edit');
            Route::patch('update/{id}', 'VideoController@update')->name('update');
            Route::get('delete/{id}', 'VideoController@delete')->name('delete');
        });

        Route::name('banner.')->prefix('banner')->group( function () {
            Route::get('', 'BannerController@index')->name('index');
            Route::get('data', 'BannerController@data')->name('data');
            Route::get('create', 'BannerController@create')->name('create');
            Route::post('store', 'BannerController@store')->name('store');
            Route::get('edit/{id}', 'BannerController@edit')->name('edit');
            Route::patch('update/{id}', 'BannerController@update')->name('update');
            Route::get('delete/{id}', 'BannerController@delete')->name('delete');
        });

        Route::name('exchange-rate.')->prefix('exchange-rate')->group( function () {
            Route::get('', 'ExchangeRateController@index')->name('index');
            Route::get('data', 'ExchangeRateController@data')->name('data');
            Route::get('create', 'ExchangeRateController@create')->name('create');
            Route::get('import', 'ExchangeRateController@import')->name('import');
            Route::post('store', 'ExchangeRateController@store')->name('store');
            Route::post('storeData', 'ExchangeRateController@storeData')->name('storeData');
            Route::get('edit/{id}', 'ExchangeRateController@edit')->name('edit');
            Route::patch('update/{id}', 'ExchangeRateController@update')->name('update');
            Route::get('delete/{id}', 'ExchangeRateController@delete')->name('delete');
        });

        Route::name('deposito.')->prefix('deposito')->group( function () {
            Route::get('', 'DepositoController@index')->name('index');
            Route::get('data', 'DepositoController@data')->name('data');
            Route::get('create', 'DepositoController@create')->name('create');
            Route::post('store', 'DepositoController@store')->name('store');
            Route::get('edit/{id}', 'DepositoController@edit')->name('edit');
            Route::patch('update/{id}', 'DepositoController@update')->name('update');
            Route::get('delete/{id}', 'DepositoController@delete')->name('delete');
        });
    });

    Route::name('master.')->prefix('master')->namespace('Master')->group( function () {

        Route::name('location.')->prefix('location')->group( function () {
            Route::get('', 'LocationController@index')->name('index');
            Route::get('data', 'LocationController@data')->name('data');
            Route::get('create', 'LocationController@create')->name('create');
            Route::post('store', 'LocationController@store')->name('store');
            Route::get('edit/{id}', 'LocationController@edit')->name('edit');
            Route::patch('update/{id}', 'LocationController@update')->name('update');
            Route::get('delete/{id}', 'LocationController@delete')->name('delete');
        });

        Route::name('device.')->prefix('device')->group( function () {
            Route::get('', 'DeviceController@index')->name('index');
            Route::get('data', 'DeviceController@data')->name('data');
            Route::get('create', 'DeviceController@create')->name('create');
            Route::post('store', 'DeviceController@store')->name('store');
            Route::get('edit/{id}', 'DeviceController@edit')->name('edit');
            Route::patch('update/{id}', 'DeviceController@update')->name('update');
            Route::get('delete/{id}', 'DeviceController@delete')->name('delete');

            Route::get('ping/{id}', 'DeviceController@ping')->name('pinger');
            Route::get('sync_data/{id}', 'DeviceController@syncData')->name('sync_data');
            Route::get('sync_file/{id}', 'DeviceController@syncFile')->name('sync_file');
            Route::get('refresh/{id}', 'DeviceController@refresh')->name('refresh');
        });
    });

});

Route::name('display.')->prefix('display')->namespace('Signage')->group( function () {

    Route::get('{device}', 'DisplayController@index');
});

Route::get('refresh_cek/{id}', 'Master\DeviceController@refreshCek')->name('refresh_cek');
