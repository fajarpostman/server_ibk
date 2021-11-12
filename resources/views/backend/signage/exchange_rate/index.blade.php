@extends('layouts.app')
@section('title_page', ucfirst(__('backend.exchange_rate')))
@section('description_page', ucfirst( __('backend.exchange_rate') . ' list'))

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
        exchangerate_table;
    });

    var exchangerate_table = $('#exchangerate_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('signage.exchange-rate.data') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'country', name: 'country' },
            { data: 'type', name: 'type' },
            { data: 'bank_buy', name: 'bank_buy' },
            { data: 'bank_sell', name: 'bank_sell' },
            { data: 'start_date', name: 'start_date' },
            { data: 'end_date', name: 'end_date' },
            // { data: 'always_showing', name: 'always_showing' },
            { data: 'updated_at', name: 'updated_at' },
            { data: 'action', name: 'action' },
        ],
        "lengthChange": false,
        "language": {
            "info": "{{ __('backend.show') }} _START_ {{ __('backend.to') }} _END_ {{ __('backend.from') }} _TOTAL_ {{ __('backend.exchange_rates') }}",
            "paginate": {
                "previous": "{{ __('backend.previous') }}",
                "next": "{{ __('backend.next') }}"
            },
            "sLengthMenu": "{{ __('backend.show') }} _MENU_",
            "emptyTable": "{{ __('backend.datatables.data_empty') }}",
            "search": "{{ __('backend.search') }}"
        }
    });
</script>
@endpush

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ ucfirst(__('backend.exchange_rate') . ' data list') }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <a href="{{ route('signage.exchange-rate.create') }}" type="button" class="btn btn-success">@lang('backend.add') @lang('backend.exchange_rate')</a>
                <table id="exchangerate_table" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>@lang('backend.exchange_rate_page.country')</th>
                            <th>@lang('backend.exchange_rate_page.type')</th>
                            <th>@lang('backend.exchange_rate_page.bank_buy')</th>
                            <th>@lang('backend.exchange_rate_page.bank_sell')</th>
                            <th>@lang('backend.start_date')</th>
                            <th>@lang('backend.end_date')</th>
                            {{-- <th>@lang('backend.always_showing')</th> --}}
                            <th>@lang('backend.exchange_rate_page.updated_at')</th>
                            <th>@lang('backend.action')</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>
@endsection
