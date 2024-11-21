@extends('layouts.main')
@section('page-title')
    {{ __('Manage Debit Notes') }}
@endsection
@section('page-breadcrumb')
    {{ __('Debit Note') }}
@endsection
@push('script-page')
    <script>
        $(document).on('change', '#bill', function() {

            var id = $(this).val();
            var url = "{{ route('bill.get') }}";

            $.ajax({
                url: url,
                type: 'get',
                cache: false,
                data: {
                    'bill_id': id,

                },
                success: function(data) {
                    $('#amount').val(data)
                },

            });

        })
    </script>
@endpush
@push('css')
    @include('layouts.includes.datatable-css')
@endpush
@push('scripts')
    @include('layouts.includes.datatable-js')
    {{ $dataTable->scripts() }}
@endpush
@section('page-action')
    <div class="float-end">
        @permission('debitnote create')
            <a href="#" data-url="{{ route('bill.custom.debit.note') }}" data-ajax-popup="true"
                data-title="{{ __('Create New Debit Note') }}" data-bs-toggle="tooltip" title="{{ __('Create') }}"
                class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection


@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    {{ $dataTable->table(['width' => '100%']) }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
