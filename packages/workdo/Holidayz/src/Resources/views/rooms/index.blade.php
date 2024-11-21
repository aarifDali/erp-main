@extends('layouts.main')

@section('page-title', __('Manage Room Type'))

@section('page-action')
    @permission('rooms create')
    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-end">
        <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create Room Type') }}"
            data-url="{{ route('hotel-rooms.create') }}" data-toggle="tooltip" title="{{ __('Create Room Type') }}">
            <i class="ti ti-plus"></i>
        </a>
    </div>
    @endpermission
@endsection

@push('css')
    <style>
        .room-image {
            width: 100px;
        }
    </style>
    <link href="{{  asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css')  }}" rel="stylesheet">

    @include('layouts.includes.datatable-css')
@endpush

@php
    $logo = get_file('uploads/rooms');
@endphp

@section('page-breadcrumb')
    {{ __('Rooms') }},
    {{ __('Room Type') }}
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
    <script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>
@endpush