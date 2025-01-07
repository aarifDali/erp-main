@extends('layouts.main')
@section('page-title')
    {{ __('Manage Daily Report') }}
@endsection
@section('page-breadcrumb')
    {{ __('Daily Report List') }}
@endsection
@push('css')
    @include('layouts.includes.datatable-css')
@endpush
@php
    $company_settings = getCompanyAllSetting();
@endphp
@section('page-action')
    <div>
        @if (Auth::user()->type == 'staff')
            @if ($todayDailyReport)
                <a href="{{ route('daily-report.edit', $todayDailyReport->id) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                    title="{{ __('Create') }}">
                    <i class="ti ti-plus"></i> {{ __('Add Daily Report')}}
                </a>  
            @else
                <a href="{{ route('daily-report.create', 0) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                    title="{{ __('Create') }}">
                    <i class="ti ti-plus"></i> {{ __('Add Daily Report')}}
                </a>
            @endif
        @endif         
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