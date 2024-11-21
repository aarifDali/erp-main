    <div class="tab-content tab-bordered">
        <div class="tab-pane fade show active" id="tab-1" role="tabpanel">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card m-1">
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-4"><span class="h6 text-sm mb-0">{{ __('Customer Name') }}</span></dt>
                                <dd class="col-sm-8"><span
                                        class="text-sm">{{ $booking->user_id != 0 ? $booking->getUserDetails->name : $booking->first_name }}</span>
                                </dd>

                                <dt class="col-sm-4"><span class="h6 text-sm mb-0">{{ __('Booking No') }}</span></dt>
                                <dd class="col-sm-8"><span
                                        class="text-sm">{{ \App\Models\Utility::bookingNumberFormat($booking->booking_number) }}</span>
                                </dd>

                                <dt class="col-sm-4"><span class="h6 text-sm mb-0">{{ __('Payment Method') }}</span>
                                </dt>
                                <dd class="col-sm-8"><span class="text-sm">{{ $booking->payment_method }}</span></dd>

                                <dt class="col-sm-4"><span class="h6 text-sm mb-0">{{ __('Payment Status') }}</span>
                                </dt>
                                <dd class="col-sm-8"><span class="text-sm">
                                        @if ($booking->payment_status == 1)
                                            <span class="badge rounded-pill bg-primary">{{ __('Paid') }}</span>
                                        @else
                                            <span class="badge rounded-pill bg-danger">{{ __('Unpaid') }}</span>
                                        @endif
                                    </span></dd>

                                <dt class="col-sm-4"><span class="h6 text-sm mb-0">{{ __('Invoice') }}</span></dt>
                                <dd class="col-sm-8"><span class="text-sm">
                                        @if ($booking->invoice)
                                            {{ $booking->invoice }}
                                        @else
                                            {{ __('-') }}
                                        @endif

                                    </span></dd>
                            </dl>
                            <hr>

                            @foreach ($booking->GetBookingOrderDetails as $item)
                                <dl class="row">
                                    <dt class="col-sm-4"><span class="h6 text-sm mb-0">{{ __('Room No') }}</span></dt>
                                    <dd class="col-sm-8"><span
                                            class="text-sm">{{ $item->getRoomDetails ? $item->getRoomDetails->room_type : '' }}</span>
                                    </dd>

                                    <dt class="col-sm-4"><span class="h6 text-sm mb-0">{{ __('Check In') }}</span></dt>
                                    <dd class="col-sm-8"><span
                                            class="text-sm">{{ \Auth::user()->dateFormat($item->check_in) }}</span>
                                    </dd>

                                    <dt class="col-sm-4"><span class="h6 text-sm mb-0">{{ __('Check Out') }}</span>
                                    </dt>
                                    <dd class="col-sm-8"><span
                                            class="text-sm">{{ \Auth::user()->dateFormat($item->check_out) }}</span>
                                    </dd>

                                    <dt class="col-sm-4"><span class="h6 text-sm mb-0">{{ __('adults') }}</span></dt>
                                    <dd class="col-sm-8"><span
                                            class="text-sm">{{ $item->getRoomDetails->adults }}</span></dd>

                                    <dt class="col-sm-4"><span class="h6 text-sm mb-0">{{ __('children') }}</span></dt>
                                    <dd class="col-sm-8"><span
                                            class="text-sm">{{ $item->getRoomDetails->children }}</span></dd>

                                </dl>
                                <hr>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
