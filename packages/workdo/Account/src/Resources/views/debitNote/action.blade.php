<span>
    @permission('edit debit note')
            <div class="action-btn  me-2">
                <a data-url="{{ route('bill.edit.debit.note',[$debitNote->bill,$debitNote->id]) }}" data-ajax-popup="true" data-title="{{__('Edit Debit Note')}}"  class="mx-3 btn btn-sm align-items-center bg-info" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                    <i class="ti ti-pencil text-white"></i>
                </a>
            </div>
        @endpermission
        @permission('edit debit note')
            <div class="action-btn">
                {!! Form::open(['method' => 'DELETE', 'route' => array('bill.delete.debit.note', $debitNote->bill,$debitNote->id),'id'=>'delete-form-'.$debitNote->id]) !!}

                <a  class="bg-danger mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$debitNote->id}}').submit();">
                    <i class="ti ti-trash text-white"></i>
                </a>
                {!! Form::close() !!}
            </div>
        @endpermission
    </span>
