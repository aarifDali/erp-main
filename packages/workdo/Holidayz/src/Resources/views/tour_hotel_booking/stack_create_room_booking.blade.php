@permission('rooms booking create')
        <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="lg"
            data-title="{{ __('Create New Booking') }}" data-url="{{ route('tour-hotelroom-booking.create',['tour_id' => $tour_id]) }}" data-toggle="tooltip"
            title="{{ __('Create New Booking') }}">
            <i class="ti ti-plus"></i>
        </a>
@endpermission
