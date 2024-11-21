@extends('layouts.main')

@section('page-title', __('Manage Coupon'))

@section('page-action')
    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-end">
        @permission('customer coupon create')
            <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create Coupon') }}"
                data-url="{{ route('room-booking-coupon.create') }}" data-toggle="tooltip" title="{{ __('Create Coupon') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection

@push('css')
    @include('layouts.includes.datatable-css')
@endpush

@section('page-breadcrumb')
    {{ __('Coupon') }}
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