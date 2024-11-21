@extends('layouts.main')

@section('page-title', __('Manage Custom Pages'))

@section('page-action')
    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-end">
        @permission('custom pages create')
            <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="lg"
                data-title="{{ __('Create Custom Page') }}" data-url="{{ route('hotel-custom-page.create') }}" data-toggle="tooltip"
                title="{{ __('Create Custom Page') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection

@section('page-breadcrumb')
    {{ __('Custom Pages') }}
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
