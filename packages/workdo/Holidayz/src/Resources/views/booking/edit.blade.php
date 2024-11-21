@extends('layouts.main')

@section('page-title', __('Booking Edit'))

@section('page-action')
    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-end">
        <a href="{{ route('hotel-room-booking.index') }}" class="btn-submit btn btn-sm btn-primary " data-toggle="tooltip" title="" data-bs-original-title="Back">
            <i class=" ti ti-arrow-back-up"></i>
        </a>
    </div>
@endsection

@section('page-breadcrumb')
    {{ __('Booking') }},
    {{ __('Booking Edit') }}
@endsection

@section('content')
<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table mb-0 pc-dt-simple" id="assets">
                    <thead>
                        <tr>

                            <th width="10px">{{ __('Booking No.') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Room') }}</th>
                            <th>{{ __('Check In') }}</th>
                            <th>{{ __('Check Out') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Total Room') }}</th>
                            <th>{{ __('Service Charge') }}</th>
                            <th width="100px">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookingorders as $booking)
                            <tr>
                                <td>{{ \Workdo\Holidayz\Entities\RoomBooking::bookingNumberFormat($booking->getBookingDetails->booking_number) }}</td>
                                <td>{{ $booking->getRoomDetails->apartmentType->name }}</td>
                                <td>{{ $booking->getRoomDetails->room_type }}</td>
                                <td>{{ company_date_formate($booking->check_in)  }}</td>
                                <td>{{ company_date_formate($booking->check_out)  }}</td>
                                <td>{{ currency_format_with_sym($booking->amount_to_pay) }}</td>
                                <td>{{ $booking->room }}</td>
                                <td>{{ currency_format_with_sym($booking->service_charge) }}</td>
                                <td width="100px">
                                    <div class="d-flex">

                                        @permission('rooms booking edit')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                    data-url="{{ route('bookingorder.edit', $booking->id) }}"
                                                    class="dropdown-item" data-ajax-popup="true"
                                                    data-bs-toggle="tooltip" data-size="lg"
                                                    data-bs-original-title="{{ __('Edit Booking Order') }}"
                                                    data-title="{{ __('Edit Booking Order') }}">
                                                    <span class="text-white">
                                                        <i class="ti ti-pencil"></i>
                                                    </span>
                                                </a>
                                            </div>
                                        @endpermission
                                        @permission('rooms booking delete')
                                            <div class="action-btn bg-danger ms-2">
                                                {{ Form::open(['route' => ['bookingorder.destroy', $booking->id], 'id' => 'delete-form-' . $booking->id]) }}
                                                @method('DELETE')
                                                <a href="#"
                                                    class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                    data-bs-toggle="tooltip" title=""
                                                    data-bs-original-title="Delete Booking" aria-label="Delete"
                                                    data-confirm-yes="delete-form-{{ $booking->id }}"><i
                                                        class="ti ti-trash text-white text-white"></i></a>
                                                {{ Form::close() }}
                                            </div>
                                        @endpermission
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
