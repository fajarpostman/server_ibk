@extends('layouts.app')
@section('title_page', ucfirst(__('backend.device')))
@section('description_page', ucfirst( __('backend.device') . ' list'))

@push('css')
<!-- DataTables -->
<link rel="stylesheet"
    href="{{ asset('AdminLTE/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endpush

@push('js')
<!-- DataTables -->
<script src="{{ asset('AdminLTE/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('AdminLTE/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function () {
        device_table;
    });

    var device_table = $('#device_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('master.device.data') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'location_id', name: 'location_id' },
            { data: 'device', name: 'device' },
            { data: 'online', name: 'online' },
            { data: 'ip_address', name: 'ip_address' },
            { data: 'location', name: 'location' },
            // { data: 'note', name: 'note' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action' },
        ],
        "lengthChange": false,
        "language": {
            "info": "{{ __('backend.show') }} _START_ {{ __('backend.to') }} _END_ {{ __('backend.from') }} _TOTAL_ {{ __('backend.devices') }}",
            "paginate": {
                "previous": "{{ __('backend.previous') }}",
                "next": "{{ __('backend.next') }}"
            },
            "sLengthMenu": "{{ __('backend.show') }} _MENU_",
            "emptyTable": "{{ __('backend.datatables.data_empty') }}",
            "search": "{{ __('backend.search') }}"
        }
    });

    function dataTableReload() {
        device_table.ajax.reload();
    }

    $('#device_table tbody').on('click', '.btn-sync-data',function (e) {
        e.preventDefault();
        var id = $( this ).attr('data-id');
        console.log(id);

        // $('#loading_table').html('<tr><td colspan="8" style="align:center"><center><i class="fa fa-refresh fa-spin"></i></center></td></tr>');
        $('#dialog_sync').html('<div class="alert alert-success alert-dismissable" role="alert"> <center><i class="fa fa-refresh fa-spin"></i></center> </div>');

        $.ajax({
            url: 'device/sync_data/' + id,
            type: 'GET',
            success: function (data) {
                // dataTableReload();
                $('#dialog_sync').html(data);
            }
        });
    });

    $('#device_table tbody').on('click', '.btn-sync-file',function (e) {
        e.preventDefault();
        var id = $( this ).attr('data-id');
        console.log(id);

        // $('#loading_table').html('<tr><td colspan="8" style="align:center"><center><i class="fa fa-refresh fa-spin"></i></center></td></tr>');

        $('#dialog_sync').html('<div class="alert alert-success alert-dismissable" role="alert"> <center><i class="fa fa-refresh fa-spin"></i></center> </div>');
        $.ajax({
            url: 'device/sync_file/' + id,
            type: 'GET',
            success: function (data) {
                // dataTableReload();
                $('#dialog_sync').html(data);
            }
        });
    });

    $('#device_table tbody').on('click', '.btn-sync-refresh',function (e) {
        e.preventDefault();
        var id = $( this ).attr('data-id');
        console.log(id);

        $('#dialog_sync').html('<div class="alert alert-success alert-dismissable" role="alert"> <center><i class="fa fa-refresh fa-spin"></i></center> </div>');
        $.ajax({
            url: 'device/refresh/' + id,
            type: 'GET',
            success: function (data) {
                // dataTableReload();
                $('#dialog_sync').html(data);
            }
        });
    });
</script>
@endpush

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ ucfirst(__('backend.device') . ' data list') }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <a href="{{ route('master.device.create') }}" type="button" class="btn btn-success">@lang('backend.add') @lang('backend.device')</a>

                <table id="device_table" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>@lang('backend.device_page.branch')</th>
                            <th>@lang('backend.device_page.device')</th>
                            <th>@lang('backend.device_page.status')</th>
                            <th>@lang('backend.device_page.ip_address')</th>
                            <th>@lang('backend.device_page.location')</th>
                            {{-- <th>@lang('backend.device_page.note')</th> --}}
                            <th>@lang('backend.created_at')</th>
                            <th>@lang('backend.action')</th>
                        </tr>
                    </thead>
                    <tbody id="loading_table"></tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>
@endsection
