@permission('rooms booking edit')
    <div class="action-btn bg-warning ms-2">
        <a href="#"
            class="mx-3 btn btn-sm d-inline-flex align-items-center"
            data-url="{{ route('main-booking.edit', $booking->id) }}"
            class="dropdown-item" data-ajax-popup="true"
            data-bs-toggle="tooltip" data-size="lg"
            data-bs-original-title="{{ __('Change Booking Status') }}"
            data-title="{{ __('Change Booking Status') }}">
            <span class="text-white">
                <i class="ti ti-caret-right"></i></span></a>
    </div>
    <div class="action-btn bg-info ms-2">
        <a href="{{ route('hotel-room-booking.edit', $booking->id) }}"
            class="mx-3 btn btn-sm d-inline-flex align-items-center">
            <span class="text-white">
                <i class="ti ti-pencil"></i></span></a>
    </div>
@endpermission
@permission('rooms booking delete')
    <div class="action-btn bg-danger ms-2">
        {{ Form::open(['route' => ['hotel-room-booking.destroy', $booking->id], 'id' => 'delete-form-' . $booking->id]) }}
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
