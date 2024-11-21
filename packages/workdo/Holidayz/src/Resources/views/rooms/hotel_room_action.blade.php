@permission('rooms edit')
    <div class="action-btn me-2">
        <a href="#" class="bg-info btn btn-sm d-inline-flex align-items-center"
            data-url="{{ route('hotel-rooms.edit', $room->id) }}" class="dropdown-item"
            data-ajax-popup="true" data-bs-toggle="tooltip" data-size="lg" data-title="{{ __('Edit Room Type') }}"
            data-bs-original-title="{{ __('Edit') }}"> <span class="text-white">
                <i class="ti ti-pencil"></i></span></a>
    </div>
    @endpermission
    @permission('rooms delete')
    <div class="action-btn me-2">
        {{ Form::open(['route' => ['hotel-rooms.destroy', $room->id],  'id' => 'delete-form-' . $room->id,]) }}
        @method('DELETE')
        <a href="#"
            class="bg-danger btn btn-sm  align-items-center bs-pass-para show_confirm"
            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
            aria-label="Delete"
            data-confirm-yes="delete-form-{{ $room->id }}"><i
                class="ti ti-trash text-white text-white"></i></a>
        {{ Form::close() }}
    </div>
@endpermission
