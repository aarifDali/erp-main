@extends('layouts.main')

@section('page-title', __('Manage Hotel Customers'))
@section('page-action')
<div>

    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-end">
        @stack('addButtonHook')
        @permission('hotel customer create')

            <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="xl" data-title="{{ __('Create Hotel Customer') }}"
                data-url="{{ route('hotel-customer.create') }}" data-toggle="tooltip" title="{{ __('Create Hotel Customer') }}">
                <i class="ti ti-plus"></i>
            </a>
            @endpermission
        </div>
</div>

@endsection
@push('css')
    @include('layouts.includes.datatable-css')
@endpush
@section('page-breadcrumb')
    {{ __('Hotel Customers') }},
    {{ __('Customer List') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        {{ $dataTable->table(['width' => '100%']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('layouts.includes.datatable-js')
    {{ $dataTable->scripts() }}
@endpush