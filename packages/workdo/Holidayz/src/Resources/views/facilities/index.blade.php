@extends('layouts.main')

@section('page-title', __('Manage Facilities'))

@section('page-action')
    @permission('facilities create')
        <div class="text-end d-flex all-button-box justify-content-md-end justify-content-end">
            <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create Facility') }}"
                data-url="{{ route('hotel-room-facilities.create') }}" data-toggle="tooltip" title="{{ __('Create facility') }}">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endpermission
@endsection
@section('page-breadcrumb')
    {{ __('facilities') }}
@endsection

@push('css')
    @include('layouts.includes.datatable-css')
    <link href="{{  asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css')  }}" rel="stylesheet">
@endpush

@push('scripts')
    @include('layouts.includes.datatable-js')
    {{ $dataTable->scripts() }}
    <script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>
@endpush

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
