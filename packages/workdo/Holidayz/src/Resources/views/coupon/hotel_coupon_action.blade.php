@permission('customer coupon show')
    <div class="action-btn me-2">
        <a href="{{ route('room-booking-coupon.show', $coupon->id) }}"
            class="bg-warning btn btn-sm d-inline-flex align-items-center"
            data-bs-toggle="tooltip" title="{{ __('View Coupon') }}">
            <span class="text-white">
                <i class="ti ti-eye"></i></span>
        </a>
    </div>
@endpermission
@permission('customer coupon edit')
    <div class="action-btn me-2">
        <a href="#"
            class="bg-info btn btn-sm d-inline-flex align-items-center"
            data-url="{{ route('room-booking-coupon.edit', $coupon->id) }}"
            class="dropdown-item" data-ajax-popup="true"
            data-bs-toggle="tooltip" data-size="lg"
            data-bs-original-title="{{ __('Edit Coupon') }}"
            data-title="{{ __('Edit Coupon') }}">
            <span class="text-white">
                <i class="ti ti-pencil"></i></span></a>
    </div>
@endpermission
@permission('customer coupon delete')
    <div class="action-btn me-2">
        {{ Form::open(['route' => ['room-booking-coupon.destroy', $coupon->id], 'id' => 'delete-form-' . $coupon->id]) }}
        @method('DELETE')
        <a href="#"
            class="bg-danger btn btn-sm  align-items-center bs-pass-para show_confirm"
            data-bs-toggle="tooltip" title=""
            data-bs-original-title="Delete Coupon" aria-label="Delete"
            data-confirm-yes="delete-form-{{ $coupon->id }}"><i
                class="ti ti-trash text-white text-white"></i></a>
        {{ Form::close() }}
    </div>
@endpermission
