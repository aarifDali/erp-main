@extends('layouts.main')

@section('page-title', __('Dashboard'))

@section('action-button')
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('packages/workdo/Holidayz/src/Resources/assets/css/main.css') }}">
@endpush
@section('page-title')
    {{ __('Dashboard') }}
@endsection
@section('page-breadcrumb')
    {{ __('Hotel/Room Management') }}
@endsection

@php
    // $app_url = trim(env('APP_URL'), '/');
    // $hotels_data['hotel_url'] = $app_url . '/' . $hotels_data['slug'];
    $workspace = \App\Models\WorkSpace::where('id', getActiveWorkSpace())->get()->first();
@endphp

@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="theme-avtar bg-danger badge">
                                    <i class="fas fa-link"></i>
                                </div>
                                <h6 class="mb-3 mt-4">
                                    {{ __(!empty($hotels_data) ? $hotels_data->name : $workspace->name) }} </h6>
                                <div class="stats my-4 mb-2">
                                    @if($hotels_data)
                                        <a href="#" class="btn btn-primary btn-md cp_link"
                                            data-link="{{ route('hotel.home', $hotels_data->slug) }}"
                                            data-bs-whatever="{{ __('Hotel Link') }}" data-bs-toggle="tooltip"
                                            data-bs-original-title="{{ __('Create Hotel') }}"
                                            title="{{ __('Click to copy link') }}">
                                            <i class="ti ti-link"></i>
                                            {{ __('Hotel Link') }}
                                        </a>
                                    @else
                                        <a href="{{ route('settings.index') }}" class="btn btn-primary btn-md"
                                            data-bs-whatever="{{ __('Create Hotel') }}" data-bs-toggle="tooltip"
                                            data-bs-original-title="{{ __('Create Hotel') }}"
                                            title="{{ __('Click to create hotel') }}">
                                            <i class="ti ti-plus"></i>
                                            {{ __('Create Hotel') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="theme-avtar bg-info badge">
                                    <i class="ti ti-users"></i>
                                </div>
                                <p class="text-muted text-sm mt-4 mb-2">{{ __('Total') }}</p>
                                <h6 class="mb-3">{{ __('Total Customers') }}</h6>
                                <h3 class="mb-0 text-primary">{{ $customers }} </h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="theme-avtar bg-warning badge">
                                    <i class="ti ti-report-money"></i>
                                </div>
                                <p class="text-muted text-sm mt-4 mb-2">{{ __('Total') }}</p>
                                <h6 class="mb-3">{{ __('Total Invoices') }}</h6>
                                <h3 class="mb-0 text-primary">{{ $bookings }} </h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="theme-avtar bg-secondary badge">
                                    <i class="ti ti-ticket"></i>
                                </div>
                                <p class="text-muted text-sm mt-4 mb-2">{{ __('Total') }}</p>
                                <h6 class="mb-3">{{ __('Total Booking') }}</h6>
                                <h3 class="mb-0 text-primary">{{ $bookings }} </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('Bookings') }}</h5>
                            </div>
                            <div class="card-body">
                                <div id="callchart" data-color="primary" data-height="230"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-6">
                        <div class="row">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('Latest Customers') }}</h5>
                                </div>
                                <div class="card-body" style="">
                                    <div class="table-responsive">
                                        <table class="table" style="height: 200px; overflow:auto">
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
                        <div class="row">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('Invoice') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table" style="height: 200px; overflow:auto">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Name') }}</th>
                                                    <th>{{ __('Invoice') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($invoices as $invoice)
                                                    <tr class="font-style">
                                                        <td>
                                                            @if ($invoice->user_id == 0)
                                                                {{ $invoice->first_name }}
                                                            @else
                                                                {{ $invoice->getCustomerDetails->name }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="#"
                                                                class="btn btn-sm btn-icon  bg-light-secondary me-2"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Invoice" data-ajax-popup="true"
                                                                data-size="lg" data-title="Invoice"
                                                                data-url="{{ route('pdf.view', $invoice->id) }}"
                                                                data-toggle="tooltip" title="{{ __('Invoice') }}">
                                                                <i class="fa fa-file-pdf"></i>
                                                            </a>
                                                        </td>
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
                    <div class="col-xxl-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('Calendar') }}</h5>
                            </div>
                            <div class="card-body">
                                <div id='calendar' class='calendar'></div>
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

    <script>
        $(document).ready(function() {
            $('.cp_link').on('click', function() {
                var value = $(this).attr('data-link');
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(value).select();
                document.execCommand("copy");
                $temp.remove();
                toastrs('Success', '{{ __('Link copied') }}', 'success')
            });
        });
    </script>

    <script>
        // line chart
        (function() {
            @if (!empty($data['incExpLineChartData']['bookings']))
                var options = {
                    chart: {
                        height: 200,
                        type: 'area',
                        dropShadow: {
                            enabled: true,
                            color: '#000',
                            top: 18,
                            left: 7,
                            blur: 10,
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
                        width: 1,
                        curve: 'smooth'
                    },

                    series: [{
                        name: "{{ __('Bookings') }}",
                        data: {!! json_encode($data['incExpLineChartData']['bookings']) !!}
                        // data: [140,60,120,180,70,50,100,150,60]
                    }, ],

                    xaxis: {
                        categories: {!! json_encode($data['incExpLineChartData']['day']) !!},

                    },
                    colors: ['#6fd943', '#2633cb'],

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
            @endif
            var chart = new ApexCharts(document.querySelector("#callchart"), options);
            chart.render();
        })();


        // calender
        (function() {
            var etitle;
            var etype;
            var etypeclass;
            var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                themeSystem: 'bootstrap',
                initialDate: '{{ $transdate }}',
                slotDuration: '00:10:00',
                navLinks: true,
                droppable: true,
                selectable: true,
                selectMirror: true,
                editable: true,
                dayMaxEvents: true,
                handleWindowResize: true,
                events: {!! json_encode($calenderTasks) !!},

            });
            calendar.render();
        })();
    </script>
@endpush
