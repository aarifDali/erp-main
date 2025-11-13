@php

    $logo = get_file('uploads/hotel_logo');
    $defaultlogoPath = get_file('uploads/logo');
    if (auth()->guard('holiday')->user()){
        $hotel = Workdo\Holidayz\Entities\Hotels::where('workspace', auth()->guard('holiday')->user()->workspace)->get()->first();
    }else{
        $hotel = Workdo\Holidayz\Entities\Hotels::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->first();
    }
@endphp
<div class="modal-body">    
   
    <button class="btn btn-sm btn-icon right no-print mb-1" style="background-color: #0caf60; color: white;"  onclick="downloadInvoice('{{ Workdo\Holidayz\Entities\RoomBooking::bookingNumberFormat($booking->booking_number, $hotel->created_by, $hotel->workspace) }}');">
        <i class="fa fa-print"> <span>Print</span></i>
    </button>
    
    <div class="row">
        <div id="printTable">
            <!-- Your invoice content goes here -->
            <div class="container">
                <div>                
                    <div class="card shadow-none bg-transparent border mb-3" id="printTable" style="border: 1px solid #dddbe2;border-radius: 10px;">
                        <div class="card-body">
                            <div class="row ">
                                <div class="col-md-8 invoice-contact pt-0">
                                    <div class="invoice-box row">
                                        <div class="col-sm-12">
                                            <table class="table mt-0 table-responsive invoice-table table-borderless">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <a href="#">
                                                                @if(isset($hotel->invoice_logo) && !empty($hotel->invoice_logo))
                                                                    <img src="{{$logo . '/' . $hotel->invoice_logo}}" class="invoice-logo" style="border:none;display:block;outline:none;text-decoration:none;width:40%" />
                                                                @else
                                                                    <img src="{{ isset($hotel->logo) ?  $logo .'/'. $hotel->logo : $defaultlogoPath .'/'.'logo_dark.png' }}" class="invoice-logo" style="border:none;display:block;outline:none;text-decoration:none;width:40%" />
                                                                @endif
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4"></div>
                            </div>
                            <div class="row invoive-info d-print-inline-flex">
                                <div class="col-sm-4 invoice-client-info">
                                    <h6>{{ __('Booking To') }}:</h6>
                                    <h6 class="m-0">
                                        @if ($booking->user_id == 0 || $booking->user_id == null)
                                            {{ $booking->first_name }}
                                        @else
                                            {{ $booking->getCustomerDetails->name }}
                                        @endif
                                    </h6>
                                    <p class="m-0">
                                        @if ($booking->user_id == 0 || $booking->user_id == null)
                                            {{ $booking->phone ? $booking->phone : '-' }}
                                        @else
                                            {{ $booking->getCustomerDetails->mobile_phone ? $booking->getCustomerDetails->mobile_phone : '-' }}
                                        @endif
                                    </p>
                                    <p><a class="text-secondary" href="#" target="_top"><span class="__cf_email__"
                                                data-cfemail="6a0e0f07052a0d070b030644090507">{{ $booking->user_id != 0 ? $booking->getCustomerDetails->email : $booking->email }}</span></a>
                                    </p>
                                </div>
                                <div class="col-sm-4">
                                    <h6 class="m-b-20">{{ __('Payment Details') }}:</h6>
                                    <table class="table table-responsive mt-0 invoice-table invoice-order table-borderless">
                                        <tbody>
                                            <tr>
                                                <th>{{ __('Paid Via') }} :</th>
                                                <td>{{ $booking->payment_method }}</td>
                                            </tr>
                                            <tr>
                                                <th><span>{{ __('Status') }}:</span></th>
                                                <td>
                                                    @if ($booking->payment_status == 1)
                                                        <span
                                                            class="badge fix_badge bg-primary p-2 px-3 rounded">{{ __('Paid') }}</span>
                                                    @else
                                                        <span
                                                            class="badge fix_badge bg-danger p-2 px-3 rounded">{{ __('Unpaid') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-4">
                                    <h6 class="m-b-20">{{ __('Booking No.') }}</h6>
                                    <h6 class="text-uppercase text-primary">
                                        <td>{{ Workdo\Holidayz\Entities\RoomBooking::bookingNumberFormat($booking->booking_number, $hotel->created_by, $hotel->workspace) }}
                                        </td>
                                    </h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive mb-4">
                                        <table class="table invoice-detail-table" id="printTable">
                                            <thead>
                                                <tr class="thead-default">
                                                    <th>{{ __('Type') }}</th>
                                                    <th>{{ __('Room') }}</th>
                                                    <th>{{ __('Check') }}<br>{{ __('In') }}</th>
                                                    <th>{{ __('Check') }}<br>{{ __('Out') }}</th>
                                                    <th>{{ __('Rent') }}</th>
                                                    <th>{{ __('Total') }}<br>{{ __('Room') }}</th>
                                                    <th>{{ __('Service') }}<br>{{ __('Charge') }}</th>
                                                    <th>{{ __('Total') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($booking->GetBookingOrderDetails as $item)
                                                    <tr>
                                                        <td>{{ $item->apartment_type }}</td>
                                                        <td>{{ $item->getRoomDetails->room_type }}</td>
                                                        <td>{{ company_date_formate($item->check_in,$hotel->created_by,$hotel->workspace) }}</td>
                                                        <td>{{ company_date_formate($item->check_out,$hotel->created_by,$hotel->workspace) }}</td>
                                                        <td>{{ currency_format_with_sym($item->getRoomDetails->final_price,$hotel->created_by,$hotel->workspace) }}</td>
                                                        <td>{{ $item->room }}</td>
                                                        <td>{{ currency_format_with_sym($item->service_charge ? $item->service_charge : 0,$hotel->created_by,$hotel->workspace) }}
                                                        </td>
                                                        <td>{{ currency_format_with_sym($item->price + $item->service_charge,$hotel->created_by,$hotel->workspace) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="invoice-total">
                                        <table class="table invoice-table ">
                                            <tbody>
                                                @php
                                                    // Calculate subtotal from booking order details
                                                    $subTotal = $booking->GetBookingOrderDetails->sum('price') + $booking->GetBookingOrderDetails->sum('service_charge');
                                                    
                                                    // Get discount amount from booking (manual discount entered)
                                                    $discountAmount = $booking->discount_amount ?? 0;
                                                    
                                                    // Calculate coupon discount if coupon exists
                                                    $couponDiscount = 0;
                                                    if ($booking->coupon_id != 0 && $booking->getCouponDetails) {
                                                        $couponDiscount = ($subTotal * $booking->getCouponDetails->discount) / 100;
                                                    }
                                                    
                                                    // Total discount = manual discount + coupon discount
                                                    $totalDiscount = $discountAmount + $couponDiscount;
                                                    
                                                    // Use stored amount_to_pay from booking (amount after discount)
                                                    // If not available, calculate it
                                                    $finalAmount = $booking->amount_to_pay ?? ($subTotal - $totalDiscount);
                                                @endphp
                                                <tr>
                                                    <th>{{ __('Sub Total') }}:</th>
                                                    <td>{{ currency_format_with_sym($subTotal,$hotel->created_by,$hotel->workspace) }}</td>
                                                </tr>
                                                @if ($totalDiscount > 0)
                                                    <tr>
                                                        <th>{{ __('Discount') }}:</th>
                                                        <td>
                                                            @if ($discountAmount > 0 && $couponDiscount > 0)
                                                                {{ currency_format_with_sym($discountAmount,$hotel->created_by,$hotel->workspace) }} 
                                                                ({{ __('Manual') }}) + 
                                                                {{ currency_format_with_sym($couponDiscount,$hotel->created_by,$hotel->workspace) }} 
                                                                ({{ $booking->getCouponDetails->discount ?? 0 }}% {{ __('Coupon') }}) = 
                                                                <strong>{{ currency_format_with_sym($totalDiscount,$hotel->created_by,$hotel->workspace) }}</strong>
                                                            @elseif ($discountAmount > 0)
                                                                <strong>{{ currency_format_with_sym($discountAmount,$hotel->created_by,$hotel->workspace) }}</strong>
                                                            @elseif ($couponDiscount > 0)
                                                                <strong>{{ currency_format_with_sym($couponDiscount,$hotel->created_by,$hotel->workspace) }}</strong>
                                                                ({{ $booking->getCouponDetails->discount ?? 0 }}% {{ __('Coupon') }})
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td>
                                                        <hr />
                                                        <h5 class="text-primary m-r-10">{{ __('Total') }} / {{ __('Amount to Pay') }}:</h5>
                                                    </td>
                                                    <td>
                                                        <hr />
                                                        <h5 class="text-primary">
                                                            {{ currency_format_with_sym($finalAmount,$hotel->created_by,$hotel->workspace) }}</h5>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
<script>
    function downloadInvoice(bookingNumber) {
        var printContents = document.getElementById('printTable');
        
        // Use html2pdf to convert content to PDF and download
        html2pdf(printContents, {
            margin:       1,
            filename:     'Invoice_' + bookingNumber + '.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
        }).then(() => {
            // Show success message after download completes
            document.getElementById('successMessage').style.display = 'block';

            // Optionally hide the success message after 3 seconds
            setTimeout(function() {
                document.getElementById('successMessage').style.display = 'none';
            }, 3000);
        });
    }
</script>
