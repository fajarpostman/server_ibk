@extends('layouts.app')
@section('title_page', ucfirst(__('backend.location')))
@section('description_page', ucfirst( __('backend.location') . ' list'))

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
        location_table;
    });

    var location_table = $('#location_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('master.location.data') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'branch_code', name: 'branch_code' },
            { data: 'branch', name: 'branch' },
            { data: 'address', name: 'address' },
            { data: 'city', name: 'city' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action' },
        ],
        "lengthChange": false,
        "language": {
            "info": "{{ __('backend.show') }} _START_ {{ __('backend.to') }} _END_ {{ __('backend.from') }} _TOTAL_ {{ __('backend.locations') }}",
            "paginate": {
                "previous": "{{ __('backend.previous') }}",
                "next": "{{ __('backend.next') }}"
            },
            "sLengthMenu": "{{ __('backend.show') }} _MENU_",
            "emptyTable": "{{ __('backend.datatables.data_empty') }}",
            "search": "{{ __('backend.search') }}"
        }
    });

    $(document).on('submit', 'form#location_add_form', function(e) {
        e.preventDefault();
        $(this).find('button[type="submit"]');
        var data = $(this).serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            success: function(result) {
                // if (result.success === true) {
                    $('div#modal_add_location').modal('hide');
                    // toastr.success(result.msg);
                    location_table.ajax.reload();
                // } else {
                    // toastr.error(result.msg);
                // }
            },
            error: function(error) {
                console.log(error);
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
                <h3 class="box-title">{{ ucfirst(__('backend.location') . ' data list') }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <a href="{{ route('master.location.create') }}" type="button" class="btn btn-success">@lang('backend.add') @lang('backend.location')</a>
                <table id="location_table" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>@lang('backend.location_page.branch_code')</th>
                            <th>@lang('backend.location_page.branch')</th>
                            <th>@lang('backend.location_page.address')</th>
                            <th>@lang('backend.location_page.city')</th>
                            <th>@lang('backend.created_at')</th>
                            <th>@lang('backend.action')</th>
                        </tr>
                    </thead>
                    {{-- <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>@lang('backend.location_page.branch_code')</th>
                            <th>@lang('backend.location_page.branch')</th>
                            <th>@lang('backend.location_page.address')</th>
                            <th>@lang('backend.location_page.city')</th>
                            <th>@lang('backend.created_at')</th>
                            <th>@lang('backend.action')</th>
                        </tr>
                    </tfoot> --}}
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>
@endsection
