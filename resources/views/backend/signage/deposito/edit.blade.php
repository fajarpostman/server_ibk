@extends('layouts.app')
@section('title_page', ucfirst(__('backend.edit') . ' ' . __('backend.deposito')))
@section('description_page', ucfirst( __('backend.edit') . ' ' . __('backend.deposito')))

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
    })
</script>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ ucfirst(__('backend.add') . ' ' . __('backend.deposito')) }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="form-group">
                    {!! Form::open(['route' => ['signage.deposito.update', $deposito->id], 'method' => 'patch']) !!}
                    <div class="row">
                        <div class="col-xs-4">
                            <label for="tenor">Tenor</label>
                            {{ Form::text('tenor', $deposito->tenor, [
                                    'class' => 'form-control',
                                    'id' => 'tenor',
                                    'placeholder' => 'Tenor',
                                ]) }}
                        </div>
                        <div class="col-xs-4">
                            <label for="interest">@lang('backend.deposito_page.interest')</label>
                            {{ Form::text('interest', $deposito->interest, [
                                    'class' => 'form-control',
                                    'id' => 'interest',
                                    'placeholder' => __('backend.deposito_page.interest'),
                                ]) }}
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-4">
                            <label>Start Date</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                {{ Form::text('start_date', \Carbon\Carbon::parse($deposito->start_date)->format('d-m-Y H:i'), [
                                    'class' => 'form-control pull-right',
                                    'id' => 'start_date',
                                    'autocomplete' => 'off',
                                    'Placeholder' => 'Start date'
                                ]) }}
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <label>End Date</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                {{ Form::text('end_date', \Carbon\Carbon::parse($deposito->end_date)->format('d-m-Y H:i'), [
                                    'class' => 'form-control pull-right',
                                    'id' => 'end_date',
                                    'autocomplete' => 'off',
                                    'Placeholder' => 'End date'
                                ]) }}
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <label for="always_showing">
                                <br>
                                {!! Form::checkbox('always_showing', null, $deposito->always_showing) !!}
                                @lang('backend.always_showing')
                            </label>
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
                <h3 class="box-title">{{ ucfirst(__('backend.add') . ' branch of the ' . __('backend.deposito')) }} &nbsp;<input id="check-all" type="checkbox" name="branch[]" class="flat-red"></h3>
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
