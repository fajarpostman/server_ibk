@extends('layouts.app')
@section('title_page', ucfirst(__('backend.edit') . ' ' . __('backend.location')))
@section('description_page', ucfirst( __('backend.edit') . ' ' . $location->branch . ' ' . __('backend.location')))

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ ucfirst(__('backend.edit') . ' ' . __('backend.location')) }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="form-group">
                    {!! Form::open(['route' => ['master.location.update', $location->id], 'method' => 'patch']) !!}
                    <div class="row">
                        <div class="col-xs-4">
                            <label for="branch_code">Branch Code</label>
                            {{ Form::text('branch_code', $location->branch_code, [
                                    'class' => 'form-control',
                                    'id' => 'branch_code',
                                    'placeholder' => 'Branch Code',
                                ]) }}
                        </div>
                        <div class="col-xs-4">
                            <label for="branch">Branch Name</label>
                            {{ Form::text('branch', $location->branch, [
                                    'class' => 'form-control',
                                    'id' => 'branch',
                                    'placeholder' => 'Branch Name',
                                ]) }}
                        </div>
                        <div class="col-xs-4">
                            <label for="city">City</label>
                            {{ Form::text('city', $location->city, [
                                    'class' => 'form-control',
                                    'id' => 'city',
                                    'placeholder' => 'City',
                                ]) }}
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-12">
                            <label for="address">Address</label>
                            {{ Form::textarea('address', $location->address, [
                                    'rows' => 4,
                                    'class' => 'form-control',
                                    'id' => 'address',
                                    'placeholder' => 'Address',
                                ]) }}
                        </div>
                    </div>
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
