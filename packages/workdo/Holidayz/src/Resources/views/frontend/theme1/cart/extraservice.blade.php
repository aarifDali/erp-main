
<div class="cart-popup-info" style="min-width: 400px;">
    <div class="cart-popup-title w-100" >
        <h3>{{ __('Extra Services') }}</h3>
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
        <div class="col-xl-12 col-lg-12 col-12">
                <ul class="checkout-summery" style="border: 1px solid var(--border-color);padding: 20px 15px;">
                    @foreach ($data as $item)
                        <li style="font-size: large;border-bottom: 1px solid var(--border-color);border-top: 1px solid var(--border-color);padding-bottom: 20px;padding-top: 20px;">
                            <span class="cart-sum-left">{{ $item->name }}</span>
                            <span class="cart-sum-right">{{ currency_format_with_sym($item->price,$hotel->created_by,$hotel->workspace) }}</span>
                        </li>
                    @endforeach
                </ul>

        </div>
    </div>
</div>
<script>
    $(".close-cart-model").click(function () {
        $(".cart-popup").removeClass("active");
        $("body").removeClass("no-scroll");
    });
</script>
