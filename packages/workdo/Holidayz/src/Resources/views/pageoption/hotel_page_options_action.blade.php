@permission('custom pages edit')
    <div class="action-btn me-2">
        <a href="#"
            class="bg-info btn btn-sm d-inline-flex align-items-center"
            data-url="{{ route('hotel-custom-page.edit',$page->id) }}"
            class="dropdown-item" data-ajax-popup="true"
            data-bs-toggle="tooltip" data-size="lg"
            data-bs-original-title="{{ __('Edit Custom Page') }}"
            data-title="{{ __('Edit Custom Page') }}">
            <span class="text-white">
                <i class="ti ti-pencil"></i></span></a>
    </div>
@endpermission
@permission('custom pages delete')
    <div class="action-btn me-2">
        {{ Form::open(['route' => ['hotel-custom-page.destroy', $page->id], 'id' => 'delete-form-' . $page->id]) }}
        @method('DELETE')
        <a href="#"
            class="bg-danger btn btn-sm  align-items-center bs-pass-para show_confirm"
            data-bs-toggle="tooltip" title=""
            data-bs-original-title="Delete Custom Page" aria-label="Delete"
            data-confirm-yes="delete-form-{{ $page->id }}"><i
                class="ti ti-trash text-white text-white"></i></a>
        {{ Form::close() }}
    </div>
@endpermission
