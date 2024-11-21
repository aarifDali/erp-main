
@if($customer->is_disable == 0)
<span>
    @if (!empty($customer['customer_id']))
        @permission('customer show')
        <div class="action-btn  me-2">
            <a href="{{ route('customer.show',\Crypt::encrypt($customer['id'])) }}" class="mx-3 btn btn-sm align-items-center bg-warning"
            data-bs-toggle="tooltip" title="{{__('View')}}">
                <i class="ti ti-eye text-white text-white"></i>
            </a>
        </div>
        @endpermission
    @endif
    @permission('customer edit')
        <div class="action-btn  me-2">
            <a  class="mx-3 btn bg-info btn-sm  align-items-center"
                data-url="{{ route('customer.edit',$customer['id']) }}" data-ajax-popup="true"  data-size="lg"
                data-bs-toggle="tooltip" title=""
                data-title="{{ __('Edit Customer') }}"
                data-bs-original-title="{{ __('Edit') }}">
                <i class="ti ti-pencil text-white"></i>
            </a>
        </div>
    @endpermission
    @if (!empty($customer['customer_id']))
        @permission('customer delete')
            <div class="action-btn">
                {{Form::open(array('route'=>array('customer.destroy', $customer['id']),'class' => 'm-0'))}}
                @method('DELETE')
                    <a
                        class="mx-3 bg-danger btn btn-sm  align-items-center bs-pass-para show_confirm"
                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                        aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$customer['id']}}"><i
                            class="ti ti-trash text-white text-white"></i></a>
                {{Form::close()}}
            </div>
        @endpermission
    @endif
</span>
@else
<div class="action-btn">
    <span class="btn bg-dark btn-sm align-items-center"><i class="ti ti-lock text-white"></i></span>
</div>
@endif
