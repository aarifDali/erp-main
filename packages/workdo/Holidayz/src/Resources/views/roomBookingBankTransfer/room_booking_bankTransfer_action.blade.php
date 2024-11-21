@permission('rooms booking manage')
    <td>
        <div class="action-btn me-2">
            <a class="bg-primary btn btn-sm  align-items-center"
                data-url="{{ route('room-booking-bank-transfer.edit', $bank_transfer_payment->id) }}"
                data-ajax-popup="true" data-size="md"
                data-bs-toggle="tooltip" title=""
                data-title="{{ __('Request Action') }}"
                data-bs-original-title="{{ __('Action') }}">
                <i class="ti ti-caret-right text-white"></i>
            </a>
        </div>
        <div class="action-btn me-2">
            {{Form::open(array('route'=>array('room-booking-bank-transfer.destroy', $bank_transfer_payment->id),'class' => 'm-0'))}}
            @method('DELETE')
                <a class="bg-danger btn btn-sm  align-items-center bs-pass-para show_confirm"
                    data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                    aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$bank_transfer_payment->id}}"><i
                        class="ti ti-trash text-white text-white"></i></a>
            {{Form::close()}}
        </div>
    </td>
@endpermission
