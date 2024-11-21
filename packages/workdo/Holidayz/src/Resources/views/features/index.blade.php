@extends('layouts.main')

@section('page-title', __('Manage Room Features'))

@section('page-action')
    @permission('feature create')
        <div class="text-end d-flex all-button-box justify-content-md-end justify-content-end">
            <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="lg"  data-title="{{ __('Create Feature') }}"
                data-url="{{ route('hotel-room-features.create') }}" data-toggle="tooltip" title="{{ __('Create Feature') }}">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endpermission
@endsection

@push('css')
    @include('layouts.includes.datatable-css')
@endpush
@section('page-breadcrumb')
    {{ __('Rooms') }},
    {{ __('Room Features') }}
@endsection

@section('content')
    <div class="row g-0">
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