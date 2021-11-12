@extends('layouts.app')
@section('title_page', ucfirst(__('backend.edit') . ' ' . __('backend.exchange_rate')))
@section('description_page', ucfirst( __('backend.edit') . ' ' . __('backend.exchange_rate')))

@push('css')
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/iCheck/all.css') }}">
<!-- date-range-picker -->
<link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" />

<style>
.location-checkbox {
    margin-right:20px;
}

.device-checkbox {
    margin-left:20px;
}
</style>
@endpush

@push('js')
<!-- iCheck 1.0.1 -->
<script src="{{ asset('AdminLTE/plugins/iCheck/icheck.min.js') }}"></script>
<!-- date-range-picker -->
<script src="{{ asset('AdminLTE/bower_components/moment/min/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('AdminLTE/bower_components/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
<!-- select2 -->
<script type="text/javascript" src="{{ asset('AdminLTE/bower_components/select2/dist/js/select2.min.js') }}"></script>
<script>
    $(function () {

        $('#check-all').click(function () {
            @foreach ($branchs as $branch)
                $('#check-{{ $branch->id }}').prop('checked', this.checked);
                @foreach ($branch->device as $device)
                    $('#subcheck-{{ $device->id }}').prop('checked', this.checked);
                @endforeach
            @endforeach
        });

        @foreach ($branchs as $branch)
            $('#check-{{ $branch->id }}').click(function () {
            @foreach ($branch->device as $device)
                $('#subcheck-{{ $device->id }}').prop('checked', this.checked);
            @endforeach
            });
        @endforeach

        //Date range picker with time picker
        $('#start_date').datetimepicker({
            format: 'DD-MM-YYYY HH:mm',
            useCurrent: 'day'
        });
        $('#end_date').datetimepicker({
            format: 'DD-MM-YYYY HH:mm',
            useCurrent: 'day'
        });

        @include('backend.master.flag.countries')
        $('#currency_code').select2({
            data: countries,
        }).val('{{ $exchange_rate->country }}').trigger('change');
    })
</script>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ ucfirst(__('backend.add') . ' ' . __('backend.exchange_rate')) }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="form-group">
                    {!! Form::open(['route' => ['signage.exchange-rate.update', $exchange_rate->id], 'method' => 'patch']) !!}
                    <div class="row">
                        <div class="col-xs-3">
                            <label for="country">Country</label>
                            <select name="country" class="form-control select2" id="currency_code"></select>
                        </div>
                        <div class="col-xs-3">
                            <label for="type">Type</label>
                            {{ Form::select('type', ['TT' => 'TT', 'BN' => 'BN'], $exchange_rate->type, [
                                'class' => 'form-control select2',
                                'id' => 'currency_type',
                            ]) }}
                        </div>
                        <div class="col-xs-3">
                            <label for="bank_buy">Bank Buy</label>
                            {{ Form::text('bank_buy', $exchange_rate->bank_buy, [
                                    'class' => 'form-control',
                                    'id' => 'bank_buy',
                                    'placeholder' => 'Bank Buy',
                                ]) }}
                        </div>
                        <div class="col-xs-3">
                            <label for="bank_sell">Bank Sell</label>
                            {{ Form::text('bank_sell', $exchange_rate->bank_sell, [
                                    'class' => 'form-control',
                                    'id' => 'bank_sell',
                                    'placeholder' => 'Bank Sell',
                                ]) }}
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-3">
                            <label for="always_showing">
                                <br>
                                {!! Form::checkbox('always_showing', null, $exchange_rate->always_showing) !!}
                                @lang('backend.always_showing')
                            </label>
                        </div>
                        <div class="col-xs-3">
                            <label>Start Date</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                {{ Form::text('start_date', \Carbon\Carbon::parse($exchange_rate->start_date)->format('d-m-Y H:i'), [
                                    'class' => 'form-control pull-right',
                                    'id' => 'start_date',
                                    'autocomplete' => 'off',
                                    'Placeholder' => 'Start date'
                                ]) }}
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <label>End Date</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                {{ Form::text('end_date', \Carbon\Carbon::parse($exchange_rate->end_date)->format('d-m-Y H:i'), [
                                    'class' => 'form-control pull-right',
                                    'id' => 'end_date',
                                    'autocomplete' => 'off',
                                    'Placeholder' => 'End date'
                                ]) }}
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <label for="bank_buy">Nomor</label>
                            {{ Form::text('nomor', $exchange_rate->nomor, [
                                'class' => 'form-control',
                                'id' => 'bank_buy',
                                'placeholder' => 'Nomor',
                            ]) }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ ucfirst(__('backend.add') . ' branch of the ' . __('backend.exchange_rate')) }} &nbsp;<input id="check-all" type="checkbox" name="branch[]" class="flat-red"></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="form-group">
                    @php
                        $indexing = 1;
                    @endphp
                    @forelse ($branchs as $branch)
                        @if ($indexing == 4)
                            <div class="row">
                                <div class="col-xs-3">
                                    <div class="checkbox-data">
                                            <label class="location-checkbox">
                                                <input id="check-{{ $branch->id }}" type="checkbox" name="branch[]" class="flat-red">
                                                {{ $branch->branch_code }}
                                            <br>
                                            @forelse ($branch->device as $device)
                                                <label class="device-checkbox">
                                                    <input id="subcheck-{{ $device->id }}" type="checkbox" name="device[]" class="flat-red" value="{{ $device->id }}" {{ in_array($device->id, $display_checked) ? 'checked' : '' }}>
                                                    (ID:{{ $device->id }}) {{ $device->device }} ({{ $device->location }})
                                                </label>
                                                <br>
                                                @empty
                                                NULL
                                            @endforelse
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="col-xs-3">
                                <div class="checkbox-data">
                                        <label class="location-checkbox">
                                            <input id="check-{{ $branch->id }}" type="checkbox" name="branch[]" class="flat-red">
                                            {{ $branch->branch_code }}
                                        <br>
                                        @forelse ($branch->device as $device)
                                            <label class="device-checkbox">
                                                <input id="subcheck-{{ $device->id }}" type="checkbox" name="device[]" class="flat-red" value="{{ $device->id }}" {{ in_array($device->id, $display_checked) ? 'checked' : '' }}>
                                                (ID:{{ $device->id }}) {{ $device->device }} ({{ $device->location }})
                                            </label>
                                            <br>
                                            @empty
                                            NULL
                                        @endforelse
                                    </label>
                                </div>
                            </div>
                        @endif
                    @php
                        $indexing++;
                    @endphp
                    @empty
                    NULL
                    @endforelse
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                {{ Form::button(__('backend.save_changes'), [
                    'type' => 'submit',
                    'class' => 'btn btn-success pull-left'
                    ]) }}
            </div>
            <!-- /.box-footer -->
            {{ Form::close() }}
        </div>
        <!-- /.box -->
    </div>
</div>
@endsection
