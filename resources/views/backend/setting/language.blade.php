@extends('layouts.app')
@section('title_page', ucfirst(__('backend.setting_page.language')))
@section('description_page', ucfirst( __('backend.setting_page.change') . ' ' . __('backend.setting_page.language')))

@push('js')
<!-- Select2 -->
<script src="{{ asset('AdminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

<script>
    $(function () {
        $('.select2').select2()
    });
</script>
@endpush

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ ucfirst(__('backend.setting_page.language')) }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="form-group">
                    {!! Form::open(['route' => 'setting.language.apply', 'method' => 'post']) !!}
                    <div class="row">
                        <div class="col-xs-4">
                                {{ Form::select('language', ['en' => 'English', 'id' => 'Indonesia'], null,[
                                    'class' => 'form-control select2',
                                    'id' => 'language',
                                    'style' => 'width: 150px;'
                                ]) }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                {{ Form::button(ucfirst( __('backend.setting_page.change') . ' ' . __('backend.setting_page.language')), [
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
