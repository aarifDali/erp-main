@php
    $hotel_logo = get_file('uploads/hotel_logo/');
@endphp

    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('packages/workdo/Holidayz/src/Resources/assets/css/custom.css') }}" type="text/css" />
    <link href="{{  asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css')  }}" rel="stylesheet">

@permission('holidayz manage')

    <div class="card" id="holidayz-sidenav">
        {{ Form::model($hotel_settings, ['route' => 'hotels.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
        <div class="card-header">
            <div class="row">
                <div class="col-lg-10 col-md-10 col-sm-10">
                    <h5 class="">{{ __('Hotel Settings') }}</h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4 col-12 d-flex">
                    <div class="card w-100">
                        <div class="card-header">
                            <h5 class="small-title">{{__('Hotel Logo')}}</h5>
                        </div>
                        <div class="card-body setting-card setting-logo-box p-3">
                            <div class="d-flex flex-column justify-content-between align-items-center h-100">
                                <div class="logo-content img-fluid logo-set-bg text-center py-2">
                                    <img alt="image" src="{{ isset($hotel_settings['logo']) && !empty($hotel_settings['logo']) ? $hotel_logo .'/'. $hotel_settings['logo'] : get_file(dark_logo()) }}" name="hotel_logo" class="img_setting small-logo" id="inputGroupFile01">
                                </div>
                                <div class="choose-files mt-3">
                                    <label for="hotel_logo">
                                        <div class=" bg-primary "> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                                        <input type="file" class="form-control file" name="hotel_logo" id="hotel_logo" data-filename="hotel_logo" onchange="document.getElementById('inputGroupFile01').src = window.URL.createObjectURL(this.files[0])">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-12 d-flex">
                    <div class="card w-100">
                        <div class="card-header">
                            <h5 class="small-title">{{__('Invoice Logo')}}</h5>
                        </div>
                        <div class="card-body setting-card setting-logo-box p-3">
                            <div class="d-flex flex-column justify-content-between align-items-center h-100">
                                <div class="logo-content img-fluid logo-set-bg text-center py-2">
                                    <img alt="image" src="{{ isset($hotel_settings['invoice_logo']) && !empty($hotel_settings['invoice_logo']) ? $hotel_logo .'/'. $hotel_settings['invoice_logo'] : get_file(dark_logo()) }}" name="hotel_invoice_logo" class="img_setting small-logo" id="inputGroupFile02">
                                </div>
                                <div class="choose-files mt-3">
                                    <label for="hotel_invoice_logo">
                                        <div class=" bg-primary "> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                                        <input type="file" class="form-control file" name="hotel_invoice_logo" id="hotel_invoice_logo" data-filename="hotel_invoice_logo" onchange="document.getElementById('inputGroupFile02').src = window.URL.createObjectURL(this.files[0])">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-12 d-flex">
                    <div class="row">
                        <div class="form-group col-md-12">
                            {!! Form::label('', __('Hotel Enable'), ['class' => 'form-label']) !!}
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" class="form-check-input" name="is_active" id="is_active" {{ (isset($hotel_settings['is_active']) && $hotel_settings['is_active']==1)?'checked':''}} >
                                <label class="form-check-label" for="customCheckdef1"></label>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="booking_prefix" class="form-label">{{ __('Booking Prefix') }}</label><x-required></x-required>
                            <input type="text" name="booking_prefix" class="form-control"
                                placeholder="{{ __('Booking Prefix') }}"
                                value="{{ !empty(company_setting('booking_prefix')) ? company_setting('booking_prefix') : '#BOOK' }}"
                                id="booking_prefix" required>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label('', __('Name'), ['class' => 'form-label']) !!}<x-required></x-required>
                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Hotel Name', 'required' => true]) !!}
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label('', __('Email'), ['class' => 'form-label']) !!}<x-required></x-required>
                    {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Enter Email', 'required' => true]) !!}
                </div>
                <x-mobile divClass="col-md-6" class="form-control" name="phone" label="{{__('Phone')}}" placeholder="{{__('Enter Phone No')}}" required="true"></x-mobile>
                @php
                    $rattingArray = ['0' => 'No Star', '1' => '*', '2' => '**', '3' => '***', '4' => '****', '5' => '*****'];
                @endphp
                <div class="form-group col-md-6">
                    {!! Form::label('', __('Ratting'), ['class' => 'form-label']) !!}<x-required></x-required>
                    {!! Form::select('ratting', $rattingArray, null, ['class' => 'form-control', 'required' => true]) !!}
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label('', __('Check In'), ['class' => 'form-label']) !!}<x-required></x-required>
                    {!! Form::time('check_in', null, ['class' => 'form-control', 'required' => true, 'min' => date('Y-m-d')]) !!}
                </div>

                <div class="form-group col-md-6">
                    {!! Form::label('', __('Check Out'), ['class' => 'form-label']) !!}<x-required></x-required>
                    {!! Form::time('check_out', null, ['class' => 'form-control', 'required' => true, 'min' => date('Y-m-d')]) !!}
                </div>
                <div class="form-group col-md-12">
                    {!! Form::label('', __('Short Description'), ['class' => 'form-label']) !!}<x-required></x-required>
                    {!! Form::textarea('short_description', null, ['class' => 'form-control', 'placeholder' => 'Enter Short Description', 'rows' => 3, 'required' => true]) !!}
                </div>
                <div class="form-group col-md-12">
                    {!! Form::label('', __('Address'), ['class' => 'form-label']) !!}<x-required></x-required>
                    {!! Form::textarea('address', null, ['class' => 'form-control', 'placeholder' => 'Enter Hotel Address', 'rows' => 2, 'required' => true]) !!}
                </div>
                {{-- <div class="form-group">
                    <label for="country">{{ __('Country') }}</label><x-required></x-required>
                    <select class="form-control" name="country" id="country" required>
                        <option value="India" {{ old('country', $hotel_settings->country ?? '') == 'India' ? 'selected' : '' }}>India</option>
                        <option value="Kenya" {{ old('country', $hotel_settings->country ?? '') == 'Kenya' ? 'selected' : '' }}>Kenya</option>
                        <option value="UAE" {{ old('country', $hotel_settings->country ?? '') == 'UAE' ? 'selected' : '' }}>UAE</option>
                        <option value="Canada" {{ old('country', $hotel_settings->country ?? '') == 'Canada' ? 'selected' : '' }}>Canada</option>
                        <!-- Add more countries as needed -->
                    </select>
                </div> --}}

                <div class="form-group">
                    <label for="country">{{ __('Country') }}</label><x-required></x-required>
                    <select class="form-control" name="country" id="country" required>
                        <option value="" disabled {{ old('country', $hotel_settings->country ?? '') == '' ? 'selected' : '' }}>
                            {{ __('Select a country') }}
                        </option>
                        <option value="India" {{ old('country', $hotel_settings->country ?? '') == 'India' ? 'selected' : '' }}>India</option>
                        <option value="Kenya" {{ old('country', $hotel_settings->country ?? '') == 'Kenya' ? 'selected' : '' }}>Kenya</option>
                        <option value="UAE" {{ old('country', $hotel_settings->country ?? '') == 'UAE' ? 'selected' : '' }}>UAE</option>
                        <option value="Canada" {{ old('country', $hotel_settings->country ?? '') == 'Canada' ? 'selected' : '' }}>Canada</option>
                        <!-- Add more countries as needed -->
                    </select>
                </div>
                
                
                <div class="form-group col-md-4">
                    {!! Form::label('', __('State'), ['class' => 'form-label']) !!}<x-required></x-required>
                    {!! Form::text('state', null, ['class' => 'form-control', 'placeholder' => 'Enter State', 'required' => true]) !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('', __('City'), ['class' => 'form-label']) !!}<x-required></x-required>
                    {!! Form::text('city', null, ['class' => 'form-control', 'placeholder' => 'Enter City', 'required' => true]) !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('', __('Zip Code'), ['class' => 'form-label']) !!}<x-required></x-required>
                    {!! Form::text('zip_code', null, ['class' => 'form-control', 'placeholder' => 'Enter Zip Code', 'required' => true]) !!}
                </div>
                <div class="form-group col-md-12">
                    {!! Form::label('', __('Policy'), ['class' => 'form-label']) !!}<x-required></x-required>
                    {!! Form::textarea('policy', null, ['class' => 'form-control', 'placeholder' => 'Enter Hotel Policy', 'rows' => 3, 'required' => true]) !!}
                    {{-- {!! Form::textarea('policy', null, ['class' => 'form-control summernote editor', 'id' => 'hotel-policy']) !!} --}}
                </div>
                <div class="form-group col-md-12">
                    {!! Form::label('', __('Description'), ['class' => 'form-label']) !!}<x-required></x-required>
                    {!! Form::textarea('description', null, ['class' => 'form-control editor', 'placeholder' => 'Enter Description', 'rows' => 3, 'id' => 'pc-tinymce-1', 'required' => true]) !!}
                    {{-- {!! Form::textarea('description', null, ['class' => 'form-control summernote editor', 'id' => 'hotel-description']) !!} --}}
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <input type="submit" value="{{ __('Save Changes') }}" class="btn btn-print-invoice  btn-primary m-r-10">
        </div>
        {!! Form::close() !!}
    </div>
@endpermission

@permission('themes manage')
    @if (isset($hotel_settings))
        <div class="card" id="themes-settings">
            {{ Form::open(['route' => ['hotel.changetheme', $hotel_settings->id], 'method' => 'POST']) }}
            {{ Form::hidden('themefile', null, ['id' => 'hotelthemefile']) }}
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-10">
                        <h5 class="">{{ __('Hotel Themes And Domain Settings') }}</h5>
                    </div>
                </div>
            </div>
            @php
                $themeImg = get_file('uploads/store_theme/');
            @endphp
            <div class="card-body pb-0">
                <div class="row"><div class="col-xl-4 col-lg-4 col-md-12 mb-4"><h5 class="mb-3">{{ __('Hotel Theme Settings') }}</h5>{{--remove for old design--}}
                <div class="border border-primary rounded p-3">
                    <div class="row g-2">
                        @foreach (\Workdo\Holidayz\Entities\Utility::themeOne() as $key => $v)
                            <div class="col-xl-12 col-lg-12 col-md-12">
                                <div
                                class="theme-card border-primary {{ $key }} {{ $hotel_settings['theme_dir'] == $key ? 'selected' : '' }}">
                                <div class="theme-card-inner">
                                    <div class="hotel-theme-image border  rounded">
                                        <img src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/store_theme/' . $key . '/Home.png') }}"
                                        class="color1 img-center pro_max_width pro_max_height {{ $key }}_homeimg"
                                        data-id="{{ $key }}">{{--{{ $key }}_img--}}
                                        </div>
                                        <div class="theme-content mt-3">
                                            <p class="mb-0">{{ __('Select Color') }}</p>
                                            <div class="d-flex mt-2 justify-content-between align-items-center {{ $key == 'theme10' ? 'theme10box' : '' }}"
                                                id="{{ $key }}">
                                                <div class="color-inputs">
                                                    @foreach ($v as $css => $val)
                                                        <label class="colorinput">
                                                            <input name="hotel_theme_color" id="color1-theme4" type="radio"
                                                                value="{{ $css }}"
                                                                data-theme="{{ $key }}"
                                                                data-imgpath="{{ $val['img_path'] }}"
                                                                class="colorinput-input color-{{ $loop->index++ }}"
                                                                {{ isset($hotel_settings['hotel_theme']) && $hotel_settings['hotel_theme'] == $css && $hotel_settings['theme_dir'] == $key ? 'checked' : '' }}>
                                                            <span class="border-box">
                                                                <span class="colorinput-color"
                                                                    style="background: #{{ $val['color'] }}"></span>
                                                            </span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                                @permission('themes edit')
                                                @if (isset($hotel_settings['theme_dir']) && $hotel_settings['theme_dir'] == $key)
                                                    <a href="{{ route('hotel.edittheme', [$hotel_settings->slug, $key]) }}"
                                                        class="btn btn-sm btn-primary" id="button-addon2"> <i
                                                            class="ti ti-pencil"></i>
                                                    </a>
                                                @endif
                                                @endpermission
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-12">
                <h5 class="">{{ __('Domain Settings') }}</h5>
                <div class="row">
                    {{-- domain code start --}}
                    <div class="col-md-12 py-4">
                        <div class="radio-button-group row  gy-2 mts">
                            <div class="col-sm-4">
                                <label
                                    class="btn btn-outline-primary w-100 {{ $hotel_settings['enable_storelink'] == 'on' ? 'active' : '' }}">
                                    {{ Form::radio('enable_domain', 'enable_storelink', true, ['class' => 'hotel_domain_click radio-button hotellink', 'id' => 'enable_storelink']) }}
                                    {{ __('Store Link') }}
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <label
                                    class="btn btn-outline-primary w-100 {{ $hotel_settings['enable_domain'] == 'on' ? 'active' : '' }}">
                                    {{ Form::radio('enable_domain', 'enable_domain', true, ['class' => 'hotel_domain_click radio-button', 'id' => 'enable_domain']) }}
                                    {{ __('Domain') }}
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <label
                                    class="btn btn-outline-primary w-100 {{ $hotel_settings['enable_subdomain'] == 'on' ? 'active' : '' }}">
                                    {{ Form::radio('enable_domain', 'enable_subdomain', true, ['class' => 'hotel_domain_click radio-button', 'id' => 'enable_subdomain']) }}
                                    {{ __('Sub Domain') }}
                                </label>
                            </div>
                        </div>
                        <div class="text-sm mt-2" id="hoteldomainnote" style="display: none">
                            {{ __('Note : Before add custom domain, your domain A record is pointing to our server IP :') }}{{ $serverIp }}
                            <br>
                        </div>
                    </div>
                    <div class="form-group col-md-12" id="hotelStoreLink"
                        style="{{ $hotel_settings['enable_storelink'] == 'on' ? 'display: block' : 'display: none' }}">
                        {{ Form::label('store_link', __('Hotel Link'), ['class' => 'form-label']) }}
                        <div class="input-group">
                            <input type="text" value="{{ $hotel_settings['hotel_url'] }}/holiday" id="myLinkInput"
                                class="form-control d-inline-block" aria-label="Recipient's username"
                                aria-describedby="button-addon2" readonly>
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary" type="button" onclick="myLinkFunction()"
                                    id="button-addon2"><i class="far fa-copy"></i>
                                    {{ __('Copy Link') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12 hoteldomain"
                        style="{{ $hotel_settings['enable_domain'] == 'on' ? 'display:block' : 'display:none' }}">
                        {{ Form::label('store_domain', __('Custom Domain'), ['class' => 'form-label']) }}
                        {{ Form::text('domains', $hotel_settings['domains'], ['class' => 'form-control', 'placeholder' => __('xyz.com')]) }}
                    </div>
                    <div class="form-group col-md-12 hotelsundomain"
                        style="{{ $hotel_settings['enable_subdomain'] == 'on' ? 'display:block' : 'display:none' }}">
                        {{ Form::label('store_subdomain', __('Sub Domain'), ['class' => 'form-label']) }}
                        <div class="input-group">
                            {{ Form::text('subdomain', $hotel_settings['slug'], ['class' => 'form-control', 'placeholder' => __('Enter Domain'), 'readonly']) }}
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">.{{ $subdomain_name }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- domain code end --}}
                </div>
                </div></div>
            </div>
            <div class="card-footer text-end">
                <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
            </div>
            {!! Form::close() !!}
        </div>
    @endif

@endpermission


<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/js/custom.js') }}"></script>

<script>
    function myLinkFunction() {
        var copyText = document.getElementById("myLinkInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999)
        document.execCommand("copy");
        toastrs('Success', "{{ __('Link copied') }}", 'success');
    }

    $(document).on('click', 'input[name="hotel_theme_color"]', function() {
        var eleParent = $(this).attr('data-theme');
        $('#hotelthemefile').val(eleParent);
        var imgpath = $(this).attr('data-imgpath');
        $('.' + eleParent + '_homeimg').attr('src', imgpath);
    });
    $(document).ready(function() {
        setTimeout(function(e) {
            var checked = $("input[type=radio][name='hotel_theme_color']:checked");
            $('#hotelthemefile').val(checked.attr('data-theme'));
            $('.' + checked.attr('data-theme') + '_homeimg').attr('src', checked.attr('data-imgpath'));
        }, 300);
    });
    $(".color1").click(function() {
        var dataId = $(this).attr("data-id");
        $('#' + dataId).trigger('click');
        var first_check = $('#' + dataId).find('.color-0').trigger("click");
        $(".theme-card").each(function() {
            $(".theme-card").removeClass('selected');
        });
        $('.' + dataId).addClass('selected');

    });
</script>

<script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>
