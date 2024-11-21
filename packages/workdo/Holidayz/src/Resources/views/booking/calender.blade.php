@extends('layouts.main')

@section('page-title', __('Manage Booking'))

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Booking') }}</li>
@endsection

@section('page-breadcrumb')
    {{ __('Booking') }}
@endsection


@section('page-action')
    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-end">
        <a href="{{ route('hotel-room-booking.index') }}" class="btn btn-sm btn-primary me-1" data-bs-toggle="tooltip"
            title="{{ __('Table View') }}" data-original-title="{{ __('Table View') }}">
            <i class="ti ti-table"></i>
        </a>

        @permission('rooms booking create')
            <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="lg"
                data-title="Add New Booking" data-url="{{ route('hotel-room-booking.create') }}" data-toggle="tooltip"
                title="{{ __('Create New Booking') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('packages/workdo/Holidayz/src/Resources/assets/css/main.css')}}">
@endpush

@push('scripts')
    <script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/js/main.min.js') }}"></script>
    <script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/js/custom.js') }}"></script>

    <script type="text/javascript">

        $(document).ready(function() {
            get_data();
        });

        function get_data() {
            var calender_type = $('#calender_type :selected').val();
            $('#calendar').removeClass('local_calender');
            $('#calendar').removeClass('goggle_calender');
            $('#calendar').addClass(calender_type);
            $.ajax({
                url: $("#zoom_calendar").val() + "/get_booking_data",//booking
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'calender_type': calender_type
                },
                success: function(data) {
                    (function() {
                        var etitle;
                        var etype;
                        var etypeclass;
                        var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'timeGridDay,timeGridWeek,dayGridMonth'
                            },
                            buttonText: {
                                timeGridDay: "{{ __('Day') }}",
                                timeGridWeek: "{{ __('Week') }}",
                                dayGridMonth: "{{ __('Month') }}"
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
                            events: data,
                        });
                        calendar.render();
                    })();
                }
            });
        }
    </script>
@endpush


@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5>{{ __('Calendar') }}</h5>
                        </div>

                            <input type="hidden" id="zoom_calendar" value="{{ url('/') }}">

                    </div>
                </div>
                <div class="card-body">
                    <div id='calendar' class='calendar' data-toggle="calendar"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4">{{ __('Booking List') }}</h4>
                    <ul class="event-cards list-group list-group-flush mt-3 w-100">
                        @foreach ($calandar as $event)
                            @php
                                $month = date('m', strtotime($event['start']));
                            @endphp
                            @if ($month == date('m'))
                                <li class="list-group-item card mb-3">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-auto mb-3 mb-sm-0">
                                            <div class="d-flex align-items-center">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti ti-ticket"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <h6 class="m-0">
                                                        <a href="{{ $event['url'] }}" class="fc-daygrid-event"
                                                            style="white-space: inherit;" fc-event-title="Booking">
                                                            <div class="fc-event-title-container">
                                                                <div class="fc-event-title text-primary">{{ $event['title'] }}
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </h6>
                                                    <small class="text-dark">{{ __('Check In Date : ') }}{{ company_date_formate($event['start']) }}<br>{{ __('Check Out Date : ') }}{{company_date_formate($event['end']) }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

    </div>
@endsection
