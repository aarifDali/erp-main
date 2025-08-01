@extends('layouts.main')
@section('page-title')
    {{ __('Manage Clients') }}
@endsection
@section('page-breadcrumb')
    {{ __('Clients List') }}
@endsection
@push('css')
    @include('layouts.includes.datatable-css')
@endpush
@php
    $company_settings = getCompanyAllSetting();
@endphp
@section('page-action')
    <div>
        <a href="{{ route('assistnow-clients.create', 0) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
            title="{{ __('Create') }}">
            <i class="ti ti-plus"></i> {{ __('Create Client')}}
        </a>
    </div>
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