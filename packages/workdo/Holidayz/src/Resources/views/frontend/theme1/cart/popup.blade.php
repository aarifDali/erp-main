{{-- card popup --}}

@php
    $path = get_file('uploads');
    $hotel = \Workdo\Holidayz\Entities\Hotels::where('slug',$slug)->where('is_active', '1')->first();
@endphp
<div class="cart-popup-info">
    <div class="cart-popup-title">
        <h3>{{ __('Room successfully added to your cart') }}</h3>
        <div class="cart-close-btn">
            <button type="button" name="CLOSE" class="close-btn close-cart-model">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="11" viewBox="0 0 12 11"
                    fill="none">
                    <path
                        d="M5.94141 0.155762C3.0178 0.155762 0.639648 2.5344 0.639648 5.45752C0.639648 8.38064 3.0178 10.7593 5.94141 10.7593C8.86502 10.7593 11.2432 8.38064 11.2432 5.45752C11.2432 2.5344 8.86502 0.155762 5.94141 0.155762ZM5.94141 10.0195C3.42566 10.0195 1.37943 7.97327 1.37943 5.45752C1.37943 2.94177 3.42566 0.895542 5.94141 0.895542C8.45715 0.895542 10.5034 2.94177 10.5034 5.45752C10.5034 7.97327 8.45715 10.0195 5.94141 10.0195ZM7.68235 4.23936L6.46418 5.45752L7.68235 6.67568C7.82686 6.82018 7.82686 7.05446 7.68235 7.19897C7.61035 7.27097 7.51566 7.30745 7.42097 7.30745C7.32627 7.30745 7.23158 7.27146 7.15958 7.19897L5.94141 5.98078L4.72323 7.19897C4.65123 7.27097 4.55654 7.30745 4.46185 7.30745C4.36715 7.30745 4.27246 7.27146 4.20046 7.19897C4.05595 7.05446 4.05595 6.82018 4.20046 6.67568L5.41863 5.45752L4.20046 4.23936C4.05595 4.09486 4.05595 3.86058 4.20046 3.71607C4.34496 3.57157 4.57923 3.57157 4.72373 3.71607L5.9419 4.93426L7.16007 3.71607C7.30458 3.57157 7.53884 3.57157 7.68335 3.71607C7.82687 3.86058 7.82686 4.09535 7.68235 4.23936Z"
                        fill="#000000"></path>
                </svg>
            </button>
        </div>
    </div>
    <div class="row no-gutters">
        <div class="col-xl-8 col-lg-7 col-12  product-card">
            <div class="product-card-inner">
                <div class="product-image">
                    <a href="#" class="img-wrapper" tabindex="0">
                        <img src="{{ $path . '/rooms/' . $room->image }}" alt="gridview-image" loading="lazy">
                    </a>
                </div>
                <div class="product-card-right">
                    <h4>
                        <a href="#" tabindex="-1">
                            {{ $room->room_type }}
                        </a>
                    </h4>
                    <ul class="room-info d-flex icon-wrapper">
                        @foreach ($room->features as $feature)
                            <li class="d-flex">
                                <i class="{{ $feature->icon }}"></i>
                                <span>{{ $feature->name }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <p>{!! $room->short_description !!}</p>
                    <ul class="time-info">
                        <li>
                            @php
                                $date = session('date');
                                $check_in = $date['check_in'];
                                $check_out = $date['check_out'];
                                $toDate = \Carbon\Carbon::parse($check_in);
                                $fromDate = \Carbon\Carbon::parse($check_out);
                        
                                $days = $toDate->diffInDays($fromDate);
                            @endphp
                            <b>{{ __('Time duration') }}:</b> {{ company_date_formate($date['check_in'],$hotel->created_by,$hotel->workspace) }} - {{ company_date_formate($date['check_out'],$hotel->created_by,$hotel->workspace) }}  ( {{$days}}{{ _('-night stay')}} )
                        </li>
                        <li>
                            <b>{{ __('Room occupancy') }}:</b> {{ $room->adults }} {{ __('Adults') }}, {{ $room->children }} {{ __('Children') }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5 col-12">
            <div class="chekout-wrapper">
                <div class="checkout-top-wrp">
                    <p>{{ __('There are') }} {{ isset($carts_array) ? count($carts_array) : $cart_count }} {{ __('item(s) in your cart.') }}</p>
                    <div class="price">
                        {{ currency_format_with_sym($priceSum,$hotel->created_by,$hotel->workspace) }}
                    </div>
                </div>
                <div class="checkout-bottom-wrp">
                    <ul class="checkout-summery">
                        <li>
                            <span class="cart-sum-left">{{ __('Total Rooms Cost in cart (tax incl.)') }}</span>
                            <span class="cart-sum-right">{{ currency_format_with_sym($priceSum,$hotel->created_by,$hotel->workspace) }}</span>
                        </li>
                        @php
                            $convenienceFee = 0;
                        @endphp
                        <li>
                            <span class="cart-sum-left">{{ __('Convenience Fees') }}</span>
                            <span class="cart-sum-right">{{ currency_format_with_sym($convenienceFee,$hotel->created_by,$hotel->workspace) }}</span>
                        </li>
                        <li>
                            <span class="cart-sum-left">{{ __('Service Charge') }}</span>
                            <span class="cart-sum-right">{{ currency_format_with_sym($serviceCharge,$hotel->created_by,$hotel->workspace) }}</span>
                        </li>
                        <li>
                            <span class="cart-sum-left">{{ __('Total (tax incl.)') }}</span>
                            <span class="cart-sum-right">{{ currency_format_with_sym($priceSum + $convenienceFee + $serviceCharge,$hotel->created_by,$hotel->workspace) }}</span>
                        </li>
                    </ul>
                    <div class="checkout-btn-wrp">
                        <a href="#" class="btn-secondary btn-transparent close-cart-model">
                            <span>{{ __('Continue Browsing') }}</span>
                        </a>
                        @if(isset($carts_array))
                            <a href="{{ (count($carts_array)  > 0 ) ?  route('checkout',$slug) : '#' }} " class="btn-secondary btn">
                        @else
                            <a href="{{ ($cart_count  > 0 ) ?  route('checkout',$slug) : '#' }} " class="btn-secondary btn">
                        @endif
                            <span>{{ __('checkout') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                viewBox="0 0 19 19" fill="none">
                                <path
                                    d="M9.0459 1.06299C4.5999 1.06299 0.983398 4.67949 0.983398 9.12549C0.983398 13.5715 4.5999 17.188 9.0459 17.188C13.4919 17.188 17.1084 13.5715 17.1084 9.12549C17.1084 4.67949 13.4919 1.06299 9.0459 1.06299ZM9.0459 16.063C5.22015 16.063 2.1084 12.9512 2.1084 9.12549C2.1084 5.29974 5.22015 2.18799 9.0459 2.18799C12.8716 2.18799 15.9834 5.29974 15.9834 9.12549C15.9834 12.9512 12.8716 16.063 9.0459 16.063ZM12.5648 9.34082C12.5363 9.40982 12.4952 9.47199 12.4434 9.52374L10.1934 11.7737C10.0839 11.8832 9.9399 11.9387 9.7959 11.9387C9.6519 11.9387 9.50788 11.884 9.39838 11.7737C9.17863 11.554 9.17863 11.1977 9.39838 10.978L10.6884 9.68799H6.0459C5.7354 9.68799 5.4834 9.43599 5.4834 9.12549C5.4834 8.81499 5.7354 8.56299 6.0459 8.56299H10.6876L9.39764 7.27301C9.17789 7.05326 9.17789 6.69698 9.39764 6.47723C9.61739 6.25748 9.97367 6.25748 10.1934 6.47723L12.4434 8.72723C12.4952 8.77898 12.5363 8.84116 12.5648 8.91016C12.6218 9.04816 12.6218 9.20282 12.5648 9.34082Z"
                                    fill="white"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- card popup end --}}
<script>
    $(".close-cart-model").click(function () {
        $(".cart-popup").removeClass("active");
        $("body").removeClass("no-scroll");
    });
</script>
