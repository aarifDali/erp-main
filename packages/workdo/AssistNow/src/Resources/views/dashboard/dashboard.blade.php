@extends('layouts.main')

@section('page-title', __('Dashboard'))

@section('action-button')
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('packages/workdo/Holidayz/src/Resources/assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/workdo/Holidayz/src/Resources/assets/css/custom.css') }}">
@endpush
@section('page-title')
    {{ __('Dashboard') }}
@endsection
@section('page-breadcrumb')
    {{ __('Task/Service Management') }}
@endsection

@php
    $workspace = \App\Models\WorkSpace::where('id', getActiveWorkSpace())->get()->first();
@endphp
@section('content')
    <div class="row row-gap mb-4">
        <div class="col-xxl-6 col-12">
                <div class="dashboard-card d-block">
                    <img src="{{ asset('assets/images/layer.png')}}" class="dashboard-card-layer" alt="layer">
                    <div class="card-inner">
                        <div class="card-content">
                            <h2>{{ !empty($ActiveWorkspaceName) ? $ActiveWorkspaceName->name : 'Saeda' }}</h2>
                            <p>{{__('Streamline client services and staff assignments for efficient management.')}} </p>
                        </div>
                        <div class="card-icon d-flex align-items-center justify-content-center">
                            <svg width="76" height="76" viewBox="0 0 76 76" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="38" cy="22" r="10" fill="#18BF6B" opacity="0.8" />
                        
                                <circle cx="20" cy="30" r="8" fill="#55B986" opacity="0.6" />
                                <circle cx="56" cy="30" r="8" fill="#55B986" opacity="0.6" />
                        
                                <path d="M26 36 C32 42, 44 42, 50 36" stroke="#18BF6B" stroke-width="3" fill="none" />
                                <path d="M22 34 L18 40 L26 42" stroke="#18BF6B" stroke-width="3" fill="none" />
                                <path d="M54 34 L58 40 L50 42" stroke="#18BF6B" stroke-width="3" fill="none" />
                        
                                <rect x="30" y="48" width="16" height="12" fill="#18BF6B" opacity="0.8" />
                                <line x1="32" y1="50" x2="44" y2="50" stroke="white" stroke-width="2" />
                                <line x1="32" y1="54" x2="44" y2="54" stroke="white" stroke-width="2" />
                            </svg>
                        </div>                        
                    </div>
                </div>
        </div>
        <div class="col-xxl-6 col-12">
            <div class="row d-flex dashboard-wrp">
                <div class="col-sm-6 col-12 d-flex flex-wrap">
                    <div class="dashboard-project-card">
                        <div class="card-inner  d-flex justify-content-between">
                            <div class="card-content">
                                <div class="theme-avtar bg-white">
                                    <i class="ti ti-users text-danger"></i>
                                </div>
                                <h3 class="mt-3 mb-0 text-danger">{{ __('Total Clietns') }}</h3>
                            </div>
                            <h3 class="mb-0">{{ $clients }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-12 d-flex flex-wrap">
                    <div class="dashboard-project-card">
                        <div class="card-inner  d-flex justify-content-between">
                            <div class="card-content">
                                <div class="theme-avtar bg-white">
                                    <i class="ti ti-user"></i>
                                </div>
                            <h3 class="mt-3 mb-0">{{ __('Total Staffs') }}</h3>
                            </div>
                            <h3 class="mb-0">{{ $countEmployees }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-12 d-flex flex-wrap">
                    <div class="dashboard-project-card">
                        <div class="card-inner  d-flex justify-content-between">
                            <div class="card-content">
                                <div class="theme-avtar bg-white">
                                    <i class="ti ti-ticket"></i>
                                </div>
                                <a href="{{route('task-assignments.index')}}"><h3 class="mt-3 mb-0">{{ __('Total Tasks') }}</h3></a>
                            </div>
                            <h3 class="mb-0">{{ $tasks }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-12 d-flex flex-wrap">
                    <div class="dashboard-project-card">
                        <div class="card-inner  d-flex justify-content-between">
                            <div class="card-content">
                                <div class="theme-avtar bg-white">
                                    <i class="ti ti-report-money"></i>
                                </div>
                                <a href="{{route('assistnow-services.index')}}"><h3 class="mt-3 mb-0">{{ __('Total Services') }}</h3></a>
                            </div>
                            <h3 class="mb-0">{{ $services }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Tasks Completed This Month') }}</h5>
                        </div>
                        <div class="card-body">
                            <div id="taskChart" data-color="primary" data-height="230"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Latest Clients') }}</h5>
                        </div>
                        <div class="card-body" style="">
                            <div class="table-responsive custom-scrollbar h-25">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Email') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($latestCustomers as $customer)
                                            <tr class="font-style">
                                                <td>{{ $customer->name }}</td>
                                                <td>{{ $customer->email }}</td>
                                            </tr>
                                        @empty
                                            @include('layouts.nodatafound')
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/js/main.min.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>

    <script>
        (function() {
            @if (!empty($taskData['counts']))
                var options = {
                    chart: {
                        height: 230,
                        type: 'area',
                        dropShadow: {
                            enabled: true,
                            color: '#000',
                            top: 10,
                            left: 5,
                            blur: 8,
                            opacity: 0.2
                        },
                        toolbar: {
                            show: false,
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        width: 2,
                        curve: 'smooth'
                    },
                    series: [{
                        name: "{{ __('Tasks Completed') }}",
                        data: {!! json_encode($taskData['counts']) !!}
                    }],

                    xaxis: {
                        categories: {!! json_encode($taskData['dates']) !!}
                    },
                    colors: ['#34c38f'],
                    grid: {
                        strokeDashArray: 4,
                    },
                    legend: {
                        show: false,
                    },
                    yaxis: {
                        tickAmount: 3,
                    }

                };
                var chart = new ApexCharts(document.querySelector("#taskChart"), options);
                chart.render();
            @endif
        })();

    </script>
@endpush