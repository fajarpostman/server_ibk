@extends('layouts.app')
@section('title_page', 'Account Setting')
@section('description_page', 'Change account preferences')

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
    <div class="col-md-6">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Account Setting</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="form-group">
                    {!! Form::open(['route' => 'setting.account.apply', 'method' => 'post']) !!}
                    <div class="row">
                        <div class="col-xs-12">
                            <label for="username">Username</label>
                            {{ Form::text('username', $current_account->username, [
                                    'class' => 'form-control',
                                    'id' => 'username',
                                    'placeholder' => 'Username',
                                ]) }}
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-12">
                            <label for="email">Email</label>
                            {{ Form::text('email', $current_account->email, [
                                    'class' => 'form-control',
                                    'id' => 'email',
                                    'placeholder' => 'Email',
                                ]) }}
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-12">
                            <label for="password">Password</label>
                            {{ Form::password('password', [
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'placeholder' => 'Password',
                                ]) }}
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-12">
                            <label for="password_confirmation">Password Confirmation</label>
                            {{ Form::password('password_confirmation', [
                                    'class' => 'form-control',
                                    'id' => 'password_confirmation',
                                    'placeholder' => 'Password Confirmation',
                                ]) }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                {{ Form::button(ucfirst( __('backend.save_changes')), [
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
