@extends('layouts.app')
@section('title_page', ucfirst(__('backend.add') . ' ' . __('backend.device')))
@section('description_page', ucfirst( __('backend.add') . ' new ' . __('backend.device')))

@push('js')
<!-- InputMask -->
<script src="{{ asset('AdminLTE/plugins/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('AdminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

<script>
    $(function () {
        $('.select2').select2()
        $('[data-mask]').inputmask()
        $('#mac_address').keyup(function (e) {
            var r = /([a-f0-9]{2})/i;
            var str = e.target.value.replace(/[^a-f0-9:]/ig, "");
            if (e.keyCode != 8 && r.test(str.slice(-2))) {
                str = str.concat(':')
            }
            e.target.value = str.slice(0, 17);
        });
    });
</script>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ ucfirst(__('backend.add') . ' ' . __('backend.device')) }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="form-group">
                    {!! Form::open(['route' => 'master.device.store', 'method' => 'post']) !!}
                    <div class="row">
                        <div class="col-xs-4">
                            <label for="branch">@lang('backend.device_page.branch')</label>
                            {{ Form::select('branch', $branch, 1,[
                                    'class' => 'form-control select2',
                                    'id' => 'branch',
                                    'style' => 'width: 100%;'
                                ]) }}
                        </div>
                        <div class="col-xs-4">
                            <label for="device">@lang('backend.device_page.device')</label>
                            {{ Form::text('device', null, [
                                    'class' => 'form-control',
                                    'id' => 'device',
                                    'placeholder' => __('backend.device_page.device'),
                                ]) }}
                        </div>
                        <div class="col-xs-4">
                            <label for="ip_address">@lang('backend.device_page.ip_address')</label>
                            {{ Form::text('ip_address', null, [
                                    'class' => 'form-control',
                                    'data-inputmask' => "'alias': 'ip'",
                                    'data-mask',
                                    'id' => 'ip_address',
                                    'placeholder' => __('backend.device_page.ip_address'),
                                ]) }}
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-4">
                            <label for="location">@lang('backend.device_page.location')</label>
                            {{ Form::text('location', null, [
                                    'class' => 'form-control',
                                    'id' => 'location',
                                    'placeholder' => __('backend.device_page.location_placeholder'),
                                ]) }}
                        </div>
                        {{-- <div class="col-xs-6">
                            <label for="note">@lang('backend.device_page.note')</label>
                            {{ Form::text('note', null, [
                                    'class' => 'form-control',
                                    'id' => 'note',
                                    'placeholder' => __('backend.device_page.note_placeholder'),
                                ]) }}
                        </div> --}}
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                {{ Form::button(__('backend.add_data'), [
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
