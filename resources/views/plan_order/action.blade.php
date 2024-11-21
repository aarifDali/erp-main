@foreach ($userOrders as $userOrder)
    @if ($user->active_plan == $order->plan_id && $order->order_id == $userOrder->order_id && $order->is_refund == 0)
        <div class="badge p-2 px-3 ms-2">
            <a href="{{ route('order.refund', [$order->id, $order->user_id]) }}" class="mx-3 align-items-center bg-warning"
                data-bs-toggle="tooltip" title="{{ __('Refund') }}" data-original-title="{{ __('Refund') }}">
                <span class ="text-white">{{ __('Refund') }}</span>
            </a>
        </div>
    @endif
@endforeach
