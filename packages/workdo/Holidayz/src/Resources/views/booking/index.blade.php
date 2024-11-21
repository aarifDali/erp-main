@extends('layouts.main')

@section('page-title', __('Manage Booking'))

@section('page-action')
<div class="text-end d-flex all-button-box justify-content-md-end justify-content-end">
        @stack('addButtonHook')
        <a href="{{ route('calender') }}" class="btn btn-sm btn-primary mx-2" data-bs-toggle="tooltip"
            title="{{ __('Calender View') }}" data-original-title="{{ __('Calender View') }}">
            <i class="ti ti-calendar"></i>
        </a>

        @permission('rooms booking create')
            <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="lg"
                data-title="{{ __('Create New Booking') }}" data-url="{{ route('hotel-room-booking.create') }}" data-toggle="tooltip"
                title="{{ __('Create New Booking') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection

@section('page-breadcrumb')
    {{ __('Booking') }}
@endsection

@push('css')
    @include('layouts.includes.datatable-css')
@endpush

@section('content')
    <div class="row">
        <div class="mt-2" id="multiCollapseExample1">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => ['booking.list.index'], 'method' => 'GET', 'id' => 'booking-form']) }}
                    <div class="row d-flex align-items-center justify-content-end">
                        <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                            <div class="btn-box">
                                {{ Form::label('check_in', __('Check In'), ['class' => 'form-label']) }}
                                {{ Form::date('check_in', isset($_GET['check_in']) ? $_GET['check_in'] : null, ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                            <div class="btn-box">
                                {{ Form::label('check_out', __('Check Out'), ['class' => 'form-label']) }}
                                {{ Form::date('check_out', isset($_GET['check_out']) ? $_GET['check_out'] : null, ['class' => 'form-control']) }}
                            </div>
                        </div>
                        @php
                            $paymentStatus = ['' => 'Please Select', '0' => 'UnPaid', '1' => 'Paid'];
                        @endphp
                        <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12">
                            <div class="btn-box">
                                {{ Form::label('type', __('Payment Method'), ['class' => 'form-label']) }}
                                {{ Form::select('type', $paymentStatus, isset($_GET['type']) ? $_GET['type'] : '', ['class' => 'form-control select']) }}
                            </div>
                        </div>
                        <div class="col-auto float-end ms-2 mt-4">

                            <a href="#" class="btn btn-sm btn-primary"
                                onclick="document.getElementById('booking-form').submit(); return false;"
                                data-bs-toggle="tooltip" title="{{ __('Apply') }}" id="applyfilter"
                                data-original-title="{{ __('apply') }}">
                                <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                            </a>
                            <a href="{{ route('hotel-room-booking.index') }}" class="btn btn-sm btn-danger" data-toggle="tooltip" id="clearfilter"
                                data-original-title="{{ __('Reset') }}">
                                <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off"></i></span>
                            </a>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
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
    <script>
        $(document).on('click', '.reset', function() {
            $("input[name='check_in']").val('');
            $("input[name='check_out']").val('');
        });
    </script>
@endpush
