@extends('layouts.main')
@section('page-title')
    {{ __('Hotel Theme Settings') }}
@endsection
@section('page-breadcrumb')
    {{ __('Settings') }},
    {{ __('Hotel Theme Settings') }}
@endsection
@section('page-action')
    <ul class="nav nav-pills cust-nav rounded  mb-3" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="header" data-bs-toggle="pill" href="#pills-header" role="tab"
                aria-controls="pills-header" aria-selected="true">{{ __('Header') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="home" data-bs-toggle="pill" href="#pills-home" role="tab"
                aria-controls="pills-home" aria-selected="false">{{ __('Home') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="footer" data-bs-toggle="pill" href="#pills-footer" role="tab"
                aria-controls="pills-footer" aria-selected="false">{{ __('Footer') }}</a>
        </li>
    </ul>
@endsection

<style>
    hr {
        margin: 8px;
    }

    .card {
        max-height: 350px;
        overflow: auto;
        scroll-behavior: smooth;
    }

    .card::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        background-color: #F5F5F5;
    }

    .card::-webkit-scrollbar {
        width: 5px;
        background-color: #F5F5F5;
    }

    .card::-webkit-scrollbar-thumb {
        background-color: #DDDBE2;
        border: 2px solid #DDDBE2;
    }

    @media screen and (min-width:767px) {
        .card {
            min-height: 350px;
        }
    }
</style>
@section('content')
    <div class="row">
        @if (Auth::user()->type !== 'super admin')
            {{ Form::open(['route' => ['hotel.hoteledittheme', [$hotel->slug, $theme]], 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation','novalidate']) }}
            <div class="col-sm-12">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade active show" id="pills-header" role="tabpanel" aria-labelledby="pills-header">
                            
                            <div class="page-header pt-3 pb-4">
                                <div class="page-block">
                                    <div class="row gy-4 align-items-center">
                                        <div class="col-md-6">
                                            <ul class="nav nav-pills nav-fill information-tab" id="pills-tab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link @if(!session('tab') or (session('tab') and session('tab') == 1)) active @endif" id="topbar-setting-tab" data-bs-toggle="pill"
                                                        data-bs-target="#topbar-setting" type="button">{{__('Top Bar')}}</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link @if(session('tab') and session('tab') == 2) active @endif" id="header-setting-tab" data-bs-toggle="pill"
                                                        data-bs-target="#header-setting" type="button">{{__('Header')}}</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            

                <div class="row">
                    <div class="col-12">
                        <div class="tab-content" id="pills-tabContent">
        
                            <div class="tab-pane fade @if(!session('tab') or (session('tab') and session('tab') == 1)) active show @endif" id="topbar-setting" role="tabpanel" aria-labelledby="pills-user-tab-1">
                            @if (
                                $theme == 'theme1' ||
                                    $theme == 'theme6' ||
                                    $theme == 'theme7' ||
                                    $theme == 'theme8' ||
                                    $theme == 'theme9' ||
                                    $theme == 'theme10')
                                @php
                                    if(isset($getHotelThemeSetting1) && isset($getHotelThemeSetting1['enable_top_bar']) && isset($getHotelThemeSetting1['top_bar_title']) && isset($getHotelThemeSetting1['top_bar_number']) && isset($getHotelThemeSetting1['top_bar_whatsapp']) && isset($getHotelThemeSetting1['top_bar_instagram']) && isset($getHotelThemeSetting1['top_bar_twitter']) && isset($getHotelThemeSetting1['top_bar_messenger'])){
                                        $storethemesetting = $getHotelThemeSetting1;
                                    }else{
                                        $storethemesetting = Workdo\Holidayz\Entities\HotelThemeSettings::demoStoreThemeSetting($hotel->workspace, $hotel->theme_dir);
                                    }
                                @endphp
                                
                                    <div class="card offset-1" style="max-height: 750px;width: 80%;">
                                            <div class="align-items-center justify-content-between p-4 pb-0">
                                                <div class="form-check form-switch custom-switch-v1 float-end">
                                                    @if (!empty($storethemesetting['enable_top_bar']))
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            name="enable_top_bar" id="enable_top_bar"
                                                            {{ $storethemesetting['enable_top_bar'] == 'on' ? 'checked="checked"' : '' }}>
                                                    @else
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            name="enable_top_bar" id="enable_top_bar">
                                                    @endif
                                                </div>
                                            </div>
                                        <div class="card-body pt-0">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        {{ Form::label('top_bar_title', __('Top Bar Title'), ['class' => 'col-form-label']) }}
                                                        {{ Form::text('top_bar_title', !empty($storethemesetting['top_bar_title']) ? $storethemesetting['top_bar_title'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Top Bar Title')]) }}
                                                        @error('top_bar_title')
                                                            <span class="invalid-top_bar_title" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                @if ($theme == 'theme1')
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            {{ Form::label('top_bar_number', __('Top Bar Number'), ['class' => 'col-form-label']) }}
                                                            {{ Form::text('top_bar_number', !empty($storethemesetting['top_bar_number']) ? $storethemesetting['top_bar_number'] : '', ['class' => 'form-control', 'placeholder' => __('Top Bar Number')]) }}
                                                            @error('top_bar_number')
                                                                <span class="invalid-top_bar_number" role="alert">
                                                                    <strong class="text-danger">{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            {{ Form::label('top_bar_whatsapp', __('Whatsapp'), ['class' => 'col-form-label']) }}
                                                            {{ Form::text('top_bar_whatsapp', !empty($storethemesetting['top_bar_whatsapp']) ? $storethemesetting['top_bar_whatsapp'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Whatsapp')]) }}
                                                            @error('top_bar_whatsapp')
                                                                <span class="invalid-top_bar_whatsapp" role="alert">
                                                                    <strong class="text-danger">{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            {{ Form::label('top_bar_instagram', __('Instagram'), ['class' => 'col-form-label']) }}
                                                            {{ Form::text('top_bar_instagram', !empty($storethemesetting['top_bar_instagram']) ? $storethemesetting['top_bar_instagram'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Instagram')]) }}
                                                            @error('top_bar_instagram')
                                                                <span class="invalid-top_bar_instagram" role="alert">
                                                                    <strong class="text-danger">{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            {{ Form::label('top_bar_twitter', __('Twitter'), ['class' => 'col-form-label']) }}
                                                            {{ Form::text('top_bar_twitter', !empty($storethemesetting['top_bar_twitter']) ? $storethemesetting['top_bar_twitter'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Twitter')]) }}
                                                            @error('top_bar_twitter')
                                                                <span class="invalid-top_bar_twitter" role="alert">
                                                                    <strong class="text-danger">{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            {{ Form::label('top_bar_messenger', __('Messenger'), ['class' => 'col-form-label']) }}
                                                            {{ Form::text('top_bar_messenger', !empty($storethemesetting['top_bar_messenger']) ? $storethemesetting['top_bar_messenger'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Messenger')]) }}
                                                            @error('top_bar_messenger')
                                                                <span class="invalid-top_bar_messenger" role="alert">
                                                                    <strong class="text-danger">{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                            @endif
                            </div>

                            <div class="tab-pane fade @if(session('tab') and session('tab') == 2) active show @endif" id="header-setting" role="tabpanel" aria-labelledby="pills-user-tab-2">
                            @foreach ($getHotelThemeSetting as $json_key => $section)
                                @php
                                    $id = '';
                                    
                                    if ($section['section_name'] == 'Home-Brand-Logo') {
                                        $id = 'Brand_Logo';
                                    }
                                    
                                    if ($section['section_name'] == 'Home-Promotions') {
                                        $id = 'Features_Setting';
                                    }
                                    if ($section['section_name'] == 'Home-Email-Subscriber') {
                                        $id = 'Email_Subscriber_Setting';
                                    }
                                    if ($section['section_name'] == 'Home-Categories') {
                                        $id = 'Categories';
                                    }
                                    if ($section['section_name'] == 'Home-Testimonial') {
                                        $id = 'Testimonials';
                                    }
                                    if ($section['section_name'] == 'Home-Footer-1') {
                                        $id = 'Footer_1';
                                    }
                                    if ($section['section_name'] == 'Home-Footer-2') {
                                        $id = 'Footer_2';
                                    }
                                    if ($section['section_name'] == 'Banner-Image') {
                                        $id = 'Banner_Img_Setting';
                                    }
                                    if ($section['section_name'] == 'Quote') {
                                        $id = 'Quote';
                                    }
                                    if ($section['section_name'] == 'Top-Purchased') {
                                        $id = 'top_purchased';
                                    }
                                    if ($section['section_name'] == 'Product-Section-Header') {
                                        $id = 'product_header';
                                    }
                                    if ($section['section_name'] == 'Latest Product') {
                                        $id = 'latest_product';
                                    }
                                    if ($section['section_name'] == 'Central-Banner') {
                                        $id = 'Banner_Setting';
                                    }
                                    if ($section['section_name'] == 'Latest-Category') {
                                        $id = 'latest_categories';
                                    }
                                    if ($section['section_name'] == 'Latest-Products') {
                                        $id = 'latest_Products';
                                    }
                                    
                                @endphp

                                @if ($section['section_name'] == 'Banner-Image')
                                    <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                        value="{{ $section['section_name'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                        value="{{ $section['section_slug'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                        value="{{ $section['array_type'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                        value="{{ $section['loop_number'] }}">
                                    @php
                                        $loop = 1;
                                        $section = (array) $section;
                                    @endphp
                                    <div class="col-lg-6">
                                        @if (
                                            $json_key == 0 ||
                                                ($json_key - 1 > -1 && $getHotelThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h4 class="mb-0">{{ $section['section_name'] }} </h4>
                                                <div class="form-check form-switch custom-switch-v1">
                                                    <input type="hidden"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        value="off">
                                                    <input type="checkbox" class="form-check-input input-primary"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                        {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="card border   shadow-none">
                                            <div class="card-body">
                                                @php $loop1 = 1; @endphp
                                                @if ($section['array_type'] == 'multi-inner-list')
                                                    @php
                                                        $loop1 = (int) $section['loop_number'];
                                                    @endphp
                                                @endif
                                                @for ($i = 0; $i < $loop1; $i++)
                                                    <div class="row">
                                                        @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                            <?php $field = (array) $field; ?>
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                value="{{ $field['field_name'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                value="{{ $field['field_slug'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                value="{{ $field['field_help_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                value="{{ $field['field_default_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                value="{{ $field['field_type'] }}">
                                                            @if ($field['field_type'] == 'text')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <input type="text"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                class="form-control"
                                                                                value="{{ $checked1 }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @else
                                                                            <input type="text" class="form-control"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                value="{{ $field['field_default_text'] }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'text area')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                            
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <textarea name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                        @else
                                                                            <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'photo upload')
                                                                <div class="col-md-6">
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        @php
                                                                            $checked2 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']])) {
                                                                                $checked2 = $section[$field['field_slug']][$i];
                                                                            
                                                                                if (is_array($checked2)) {
                                                                                    $checked2 = $checked2['field_prev_text'];
                                                                                }
                                                                            }
                                                                            $imgdisplay = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                value="{{ $checked2 }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if (isset($checked2) && !is_array($checked2))
                                                                            <img src="{{ asset('uploads/' . $checked2) }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @endif
                                                                    @else
                                                                        @php
                                                                            $imgdisplay1 = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                            
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]"
                                                                                value="{{ $field['field_default_text'] }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if(file_exists(base_path().'/'.'uploads/'.$field['field_default_text']))
                                                                            <img src="{{ $imgdisplay1 }}{{ $field['field_default_text'] }} "
                                                                                id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                                class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                                @if (!empty($getHotelThemeSetting['dashboard'])) style=""
                                                                            @else
                                                                            style="width: auto; height: 50px;" @endif
                                                                                @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                            @else
                                                                            style="width: 200px; height: 200px;" @endif>
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }} "
                                                                                    id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                                    class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                                    @if (!empty($getHotelThemeSetting['dashboard'])) style=""
                                                                                @else
                                                                                style="width: auto; height: 50px;" @endif
                                                                                    @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                                @else
                                                                                style="width: 200px; height: 200px;" @endif>
                                                                        @endif
                                                                    @endif

                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                @endif
                            @endforeach
                        
                            @if ($theme !== 'theme3')
                                    <div class="card offset-1" style="max-height: 750px;width: 80%;">
                                        @foreach ($getHotelThemeSetting as $json_key => $section)
                                            @php
                                                $id = '';
                                                
                                                if ($section['section_name'] == 'Home-Brand-Logo') {
                                                    $id = 'Brand_Logo';
                                                }
                                                
                                                if ($section['section_name'] == 'Home-Promotions') {
                                                    $id = 'Features_Setting';
                                                }
                                                if ($section['section_name'] == 'Home-Email-Subscriber') {
                                                    $id = 'Email_Subscriber_Setting';
                                                }
                                                if ($section['section_name'] == 'Home-Categories') {
                                                    $id = 'Categories';
                                                }
                                                if ($section['section_name'] == 'Home-Testimonial') {
                                                    $id = 'Testimonials';
                                                }
                                                if ($section['section_name'] == 'Home-Footer-1') {
                                                    $id = 'Footer_1';
                                                }
                                                if ($section['section_name'] == 'Home-Footer-2') {
                                                    $id = 'Footer_2';
                                                }
                                                if ($section['section_name'] == 'Banner-Image') {
                                                    $id = 'Banner_Img_Setting';
                                                }
                                                if ($section['section_name'] == 'Quote') {
                                                    $id = 'Quote';
                                                }
                                                if ($section['section_name'] == 'Top-Purchased') {
                                                    $id = 'top_purchased';
                                                }
                                                if ($section['section_name'] == 'Product-Section-Header') {
                                                    $id = 'product_header';
                                                }
                                                if ($section['section_name'] == 'Latest Product') {
                                                    $id = 'latest_product';
                                                }
                                                if ($section['section_name'] == 'Central-Banner') {
                                                    $id = 'Banner_Setting';
                                                }
                                                if ($section['section_name'] == 'Latest-Category') {
                                                    $id = 'latest_categories';
                                                }
                                                if ($section['section_name'] == 'Latest-Products') {
                                                    $id = 'latest_Products';
                                                }
                                                
                                            @endphp
                                            @if ($section['section_name'] == 'Header')
                                                @if (
                                                    $json_key == 0 ||
                                                        ($json_key - 1 > -1 && $getHotelThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                                        <div class="align-items-center justify-content-between p-4 pb-0">
                                                            <div class="form-check form-switch custom-switch-v1 float-end">
                                                                <input type="hidden"
                                                                    name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                                    value="off">
                                                                <input type="checkbox"
                                                                    class="form-check-input input-primary"
                                                                    name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                                    id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                                    {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                            </div>
                                                        </div>
                                                @endif
                                            @endif
                                        @endforeach
                                        @foreach ($getHotelThemeSetting as $json_key => $section)
                                            @if ($section['section_name'] == 'Header')
                                                <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                                    value="{{ $section['section_name'] }}">
                                                <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                                    value="{{ $section['section_slug'] }}">
                                                <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                                    value="{{ $section['array_type'] }}">
                                                <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                                    value="{{ $section['loop_number'] }}">
                                                @php
                                                    $loop = 1;
                                                    $section = (array) $section;
                                                @endphp
                                                <div class="card-body pt-0">
                                                    @php $loop1 = 1; @endphp
                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                        @php
                                                            $loop1 = (int) $section['loop_number'];
                                                        @endphp
                                                    @endif
                                                    @for ($i = 0; $i < $loop1; $i++)
                                                        <div class="row">
                                                            @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                                <?php $field = (array) $field; ?>
                                                                <input type="hidden"
                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                    value="{{ $field['field_name'] }}">
                                                                <input type="hidden"
                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                    value="{{ $field['field_slug'] }}">
                                                                <input type="hidden"
                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                    value="{{ $field['field_help_text'] }}">
                                                                <input type="hidden"
                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                    value="{{ $field['field_default_text'] }}">
                                                                <input type="hidden"
                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                    value="{{ $field['field_type'] }}">
                                                                @if ($field['field_type'] == 'text')
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            @php
                                                                                $checked1 = $field['field_default_text'];
                                                                                if (!empty($section[$field['field_slug']][$i])) {
                                                                                    $checked1 = $section[$field['field_slug']][$i];
                                                                                }
                                                                            @endphp
                                                                            @if ($section['array_type'] == 'multi-inner-list')
                                                                                <input type="text"
                                                                                    name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                    class="form-control"
                                                                                    value="{{ $checked1 }}"
                                                                                    placeholder="{{ $field['field_help_text'] }}">
                                                                            @else
                                                                                <input type="text" class="form-control"
                                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                    value="{{ $field['field_default_text'] }}"
                                                                                    placeholder="{{ $field['field_help_text'] }}">
                                                                            @endif

                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if ($field['field_type'] == 'text area')
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            @php
                                                                                $checked1 = $field['field_default_text'];
                                                                                
                                                                                if (!empty($section[$field['field_slug']][$i])) {
                                                                                    $checked1 = $section[$field['field_slug']][$i];
                                                                                }
                                                                                
                                                                            @endphp
                                                                            @if ($section['array_type'] == 'multi-inner-list')
                                                                                <textarea name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" id=""
                                                                                    class="form-control" rows="6" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                            @else
                                                                                <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" id=""
                                                                                    class="form-control" rows="6" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if ($field['field_type'] == 'photo upload')
                                                                    <div class="col-md-6">
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            @php
                                                                                $checked2 = $field['field_default_text'];
                                                                                
                                                                                if (!empty($section[$field['field_slug']])) {
                                                                                    $checked2 = $section[$field['field_slug']][$i];
                                                                                
                                                                                    if (is_array($checked2)) {
                                                                                        $checked2 = $checked2['field_prev_text'];
                                                                                    }
                                                                                }
                                                                                $imgdisplay = get_file('uploads/');
                                                                                $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                            @endphp
                                                                            <div class="form-group">
                                                                                <label
                                                                                    class="form-label">{{ $field['field_name'] }}</label>
                                                                                <input type="hidden"
                                                                                    name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                    value="{{ $checked2 }}">
                                                                                <input type="file"
                                                                                    name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                    class="form-control"
                                                                                    placeholder="{{ $field['field_help_text'] }}">
                                                                            </div>
                                                                            @if (isset($checked2) && !is_array($checked2))
                                                                                <img src="{{ asset('uploads/' . $checked2) }}"
                                                                                    style="width: auto; max-height: 80px;">
                                                                            @else
                                                                                <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                    style="width: auto; max-height: 80px;">
                                                                            @endif
                                                                        @else
                                                                            @php
                                                                                $imgdisplay1 = get_file('uploads/');
                                                                                $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                                
                                                                            @endphp
                                                                            <div class="form-group">
                                                                                <label
                                                                                    class="form-label">{{ $field['field_name'] }}</label>
                                                                                <input type="hidden"
                                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]"
                                                                                    value="{{ $field['field_default_text'] }}">
                                                                                <input type="file"
                                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                    class="form-control"
                                                                                    placeholder="{{ $field['field_help_text'] }}">
                                                                            </div>
                                                                            @if(file_exists(base_path().'/'.'uploads/'.$field['field_default_text']))
                                                                                <img src="{{ $imgdisplay1 }}/{{ $field['field_default_text'] }} "
                                                                                    id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                                    class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                                    @if (!empty($getHotelThemeSetting['dashboard'])) style=""
                                                                                        @else
                                                                                        style="width: auto; height: 70px;" @endif
                                                                                    @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                                        @else
                                                                                        style="width: 200px; height: 200px;" @endif>
                                                                            @else
                                                                                <img src="{{ $imgdisplay }}/{{ $field['field_default_text'] }} "
                                                                                    id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                                    class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                                    @if (!empty($getHotelThemeSetting['dashboard'])) style=""
                                                                                        @else
                                                                                        style="width: auto; height: 70px;" @endif
                                                                                    @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                                        @else
                                                                                        style="width: 200px; height: 200px;" @endif>
                                                                            @endif
                                                                        @endif

                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endfor
                                                </div>
                                            @endif
                                        @endforeach
                            @endif
                            </div>
                        </div>
                    </div>
                </div>

                        <div class="col-12 text-lg-end offset-1" style="width:80%;">
                                <button type="submit" class="btn btn-primary submit_all"> <i data-feather="check-circle"
                                        class="me-2"></i>{{ __('Save Changes') }}</button>
                        </div>
                    </div></div>
                    <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home">
                            
                            <div class="page-header pt-3 pb-4">
                                <div class="page-block">
                                    <div class="row gy-4 align-items-center">
                                        <div class="col-md-12">
                                            <ul class="nav nav-pills nav-fill information-tab" id="pills-tab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link @if(!session('tab') or (session('tab') and session('tab') == 1)) active @endif" id="intro-setting-tab" data-bs-toggle="pill"
                                                        data-bs-target="#intro-setting" type="button">{{__('Intro')}}</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link @if(session('tab') and session('tab') == 2) active @endif" id="room-setting-tab" data-bs-toggle="pill"
                                                        data-bs-target="#room-setting" type="button">{{__('Room')}}</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link @if(session('tab') and session('tab') == 3) active @endif" id="about-us-setting-tab" data-bs-toggle="pill"
                                                        data-bs-target="#about-us-setting" type="button">{{__('About Us')}}</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link @if(session('tab') and session('tab') == 4) active @endif" id="our-room-setting-tab" data-bs-toggle="pill"
                                                        data-bs-target="#our-room-setting" type="button">{{__('Our Room')}}</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link @if(session('tab') and session('tab') == 5) active @endif" id="amenities-setting-tab" data-bs-toggle="pill"
                                                        data-bs-target="#amenities-setting" type="button">{{__('Amenities')}}</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link @if(session('tab') and session('tab') == 6) active @endif" id="testimonial-setting-tab" data-bs-toggle="pill"
                                                        data-bs-target="#testimonial-setting" type="button">{{__('Testimonial')}}</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link @if(session('tab') and session('tab') == 7) active @endif" id="swimming-pool-setting-tab" data-bs-toggle="pill"
                                                        data-bs-target="#swimming-pool-setting" type="button">{{__('Swimming Pool')}}</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link @if(session('tab') and session('tab') == 8) active @endif" id="offers-news-setting-tab" data-bs-toggle="pill"
                                                        data-bs-target="#offers-news-setting" type="button">{{__('Offers & News')}}</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link @if(session('tab') and session('tab') == 9) active @endif" id="instagram-setting-tab" data-bs-toggle="pill"
                                                        data-bs-target="#instagram-setting" type="button">{{__('Instagram')}}</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
            <div class="row">
                <div class="col-12">
                    <div class="tab-content" id="pills-tabContent">
                        @foreach ($getHotelThemeSetting as $json_key => $section)
                                @php
                                    $id = '';
                                    // home intro section id
                                    if ($section['section_name'] == 'Intro') {
                                        $id = 'home_intro';
                                    }
                                    
                                    if ($section['section_name'] == 'Room') {
                                        $id = 'home_room';
                                    }
                                    
                                    if ($section['section_name'] == 'About Us') {
                                        $id = 'home_abount_us';
                                    }
                                    
                                    if ($section['section_name'] == 'Our Room') {
                                        $id = 'home_our_room';
                                    }
                                    
                                    if ($section['section_name'] == 'Amenities') {
                                        $id = 'home_amenities';
                                    }
                                    
                                    if ($section['section_name'] == 'Testimonial') {
                                        $id = 'home_testimonial';
                                    }
                                    
                                    if ($section['section_name'] == 'Swimming Pool') {
                                        $id = 'home_swing';
                                    }
                                    
                                    if ($section['section_name'] == 'Blog') {
                                        $id = 'home_blog';
                                    }
                                    
                                    if ($section['section_name'] == 'Instagram') {
                                        $id = 'home_instagram';
                                    }
                                    
                                @endphp


                                {{-- home intro section start --}}
                                @if ($section['section_name'] == 'Intro')
                                <div class="tab-pane fade @if(!session('tab') or (session('tab') and session('tab') == 1)) active show @endif" id="intro-setting" role="tabpanel" aria-labelledby="pills-user-tab-1">
                                    <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                        value="{{ $section['section_name'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                        value="{{ $section['section_slug'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                        value="{{ $section['array_type'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                        value="{{ $section['loop_number'] }}">
                                    @php
                                        $loop = 1;
                                        $section = (array) $section;
                                    @endphp
                                    <div class="col-lg-12">
                                        <div class="card offset-1" style="max-height: 750px;width: 80%;">
                                            @if (
                                                $json_key == 0 ||
                                                    ($json_key - 1 > -1 && $getHotelThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                                    <div class="align-items-center justify-content-between p-4 pb-0">
                                                        <div class="form-check form-switch custom-switch-v1 float-end">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input input-primary"
                                                                name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                                id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                                {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                            @endif
                                            <div class="card-body pt-0">
                                                @php $loop1 = 1; @endphp
                                                @if ($section['array_type'] == 'multi-inner-list')
                                                    @php
                                                        $loop1 = (int) $section['loop_number'];
                                                    @endphp
                                                @endif
                                                @for ($i = 0; $i < $loop1; $i++)
                                                    <div class="row">
                                                        @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                            <?php $field = (array) $field; ?>

                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                value="{{ $field['field_name'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                value="{{ $field['field_slug'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                value="{{ $field['field_help_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                value="{{ $field['field_default_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                value="{{ $field['field_type'] }}">
                                                            @if ($field['field_type'] == 'text')
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <input type="text"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                class="form-control"
                                                                                value="{{ $checked1 }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @else
                                                                            <input type="text" class="form-control"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                value="{{ $field['field_default_text'] }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'text area')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                            
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <textarea name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" id=""
                                                                                class="form-control" rows="6" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                        @else
                                                                            <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" id=""
                                                                                class="form-control" rows="6" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'photo upload')
                                                                <div class="col-md-6">
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        @php
                                                                            $checked2 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']])) {
                                                                                $checked2 = $section[$field['field_slug']][$i];
                                                                            
                                                                                if (is_array($checked2)) {
                                                                                    $checked2 = $checked2['field_prev_text'];
                                                                                }
                                                                            }
                                                                            $imgdisplay = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                value="{{ $checked2 }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if (isset($checked2) && !is_array($checked2))
                                                                            <img src="{{ asset('uploads/' . $checked2) }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @endif
                                                                    @else
                                                                        @php
                                                                            $imgdisplay1 = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]"
                                                                                value="{{ $field['field_default_text'] }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if(file_exists(base_path().'/'.'uploads/'.$field['field_default_text']))
                                                                            <img src="{{ $imgdisplay1 }}/{{ $field['field_default_text'] }} "
                                                                                id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                                class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                                @if (!empty($getHotelThemeSetting['dashboard'])) style=""
                                                                            @else
                                                                            style="width: auto; height: 70px;" @endif
                                                                                @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                            @else
                                                                            style="width: 200px; height: 200px;" @endif>
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}/{{ $field['field_default_text'] }} "
                                                                                    id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                                    class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                                    @if (!empty($getHotelThemeSetting['dashboard'])) style=""
                                                                                @else
                                                                                style="width: auto; height: 70px;" @endif
                                                                                    @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                                @else
                                                                                style="width: 200px; height: 200px;" @endif>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div></div>
                                @endif
                                {{-- home intro section end --}}

                                {{-- home rooms section start --}}
                                @if ($section['section_name'] == 'Room')
                                <div class="tab-pane fade @if(session('tab') and session('tab') == 2) active show @endif" id="room-setting" role="tabpanel" aria-labelledby="pills-user-tab-2">
                                    <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                        value="{{ $section['section_name'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                        value="{{ $section['section_slug'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                        value="{{ $section['array_type'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                        value="{{ $section['loop_number'] }}">
                                    @php
                                        $loop = 1;
                                        $section = (array) $section;
                                    @endphp
                                    <div class="col-lg-12">
                                        <div class="card offset-1" style="max-height: 750px;width: 80%;">
                                            @if (
                                                $json_key == 0 ||
                                                    ($json_key - 1 > -1 && $getHotelThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                                    <div class="align-items-center justify-content-between p-4 pb-0">
                                                        <div class="form-check form-switch custom-switch-v1 float-end">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input input-primary"
                                                                name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                                id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                                {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                            @endif
                                            <div class="card-body pt-0">
                                                @php $loop1 = 1; @endphp
                                                @if ($section['array_type'] == 'multi-inner-list')
                                                    @php
                                                        $loop1 = (int) $section['loop_number'];
                                                    @endphp
                                                @endif
                                                @for ($i = 0; $i < $loop1; $i++)
                                                    <div class="row">
                                                        @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                            <?php $field = (array) $field; ?>

                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                value="{{ $field['field_name'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                value="{{ $field['field_slug'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                value="{{ $field['field_help_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                value="{{ $field['field_default_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                value="{{ $field['field_type'] }}">
                                                            @if ($field['field_type'] == 'text')
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <input type="text"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                class="form-control"
                                                                                value="{{ $checked1 }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @else
                                                                            <input type="text" class="form-control"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                value="{{ $field['field_default_text'] }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'text area')
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                            
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <textarea name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" id=""
                                                                                class="form-control" rows="6" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                        @else
                                                                            <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" id=""
                                                                                class="form-control" rows="6" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'photo upload')
                                                                <div class="col-md-6">
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        @php
                                                                            $checked2 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']])) {
                                                                                $checked2 = $section[$field['field_slug']][$i];
                                                                            
                                                                                if (is_array($checked2)) {
                                                                                    $checked2 = $checked2['field_prev_text'];
                                                                                }
                                                                            }
                                                                            $imgdisplay = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                value="{{ $checked2 }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if (isset($checked2) && !is_array($checked2))
                                                                            <img src="{{ asset('uploads/' . $checked2) }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @endif
                                                                    @else
                                                                        @php
                                                                            $imgdisplay = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]"
                                                                                value="{{ $field['field_default_text'] }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }} "
                                                                            id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                            class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                            @if (!empty($getHotelThemeSetting['dashboard'])) style=""
                                                                        @else
                                                                        style="width: auto; height: 50px;" @endif
                                                                            @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                        @else
                                                                        style="width: 200px; height: 200px;" @endif>
                                                                    @endif

                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div></div>
                                @endif
                                {{-- home rooms section end --}}

                                {{-- home about section start --}}
                                @if ($section['section_name'] == 'About Us')
                                <div class="tab-pane fade @if(session('tab') and session('tab') == 3) active show @endif" id="about-us-setting" role="tabpanel" aria-labelledby="pills-user-tab-3">
                                    <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                        value="{{ $section['section_name'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                        value="{{ $section['section_slug'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                        value="{{ $section['array_type'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                        value="{{ $section['loop_number'] }}">
                                    @php
                                        $loop = 1;
                                        $section = (array) $section;
                                    @endphp
                                    <div class="col-lg-12">
                                        <div class="card offset-1" style="max-height: 750px;width: 80%;">
                                            @if (
                                                $json_key == 0 ||
                                                    ($json_key - 1 > -1 && $getHotelThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                                    <div class="align-items-center justify-content-between p-4 pb-0">
                                                        <div class="form-check form-switch custom-switch-v1 float-end">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input input-primary"
                                                                name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                                id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                                {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                            @endif
                                            <div class="card-body pt-0">
                                                @php $loop1 = 1; @endphp
                                                @if ($section['array_type'] == 'multi-inner-list')
                                                    @php
                                                        $loop1 = (int) $section['loop_number'];
                                                    @endphp
                                                @endif
                                                @for ($i = 0; $i < $loop1; $i++)
                                                    <div class="row">
                                                        @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                            <?php $field = (array) $field; ?>

                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                value="{{ $field['field_name'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                value="{{ $field['field_slug'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                value="{{ $field['field_help_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                value="{{ $field['field_default_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                value="{{ $field['field_type'] }}">
                                                            @if ($field['field_type'] == 'text')
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <input type="text"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                class="form-control"
                                                                                value="{{ $checked1 }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @else
                                                                            <input type="text" class="form-control"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                value="{{ $field['field_default_text'] }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'text area')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                            
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <textarea name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" id=""
                                                                                class="form-control" rows="5" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                        @else
                                                                            <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" id=""
                                                                                class="form-control" rows="5" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'photo upload')
                                                                <div class="col-md-6">
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        @php
                                                                            $checked2 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']])) {
                                                                                $checked2 = $section[$field['field_slug']][$i];
                                                                            
                                                                                if (is_array($checked2)) {
                                                                                    $checked2 = $checked2['field_prev_text'];
                                                                                }
                                                                            }
                                                                            $imgdisplay = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                value="{{ $checked2 }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if (isset($checked2) && !is_array($checked2))
                                                                            <img src="{{ asset('uploads/' . $checked2) }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @endif
                                                                    @else
                                                                        @php
                                                                            $imgdisplay1 = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]"
                                                                                value="{{ $field['field_default_text'] }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if(file_exists(base_path().'/'.'uploads/'.$field['field_default_text']))
                                                                            <img src="{{ $imgdisplay1 }}/{{ $field['field_default_text'] }} "
                                                                                id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                                class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                                @if (!empty($getHotelThemeSetting['dashboard'])) style=""
                                                                            @else
                                                                            style="width: auto; height: 70px;" @endif
                                                                                @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                            @else
                                                                            style="width: 200px; height: 200px;" @endif>
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}/{{ $field['field_default_text'] }} "
                                                                                id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                                class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                                @if (!empty($getHotelThemeSetting['dashboard'])) style=""
                                                                            @else
                                                                            style="width: auto; height: 70px;" @endif
                                                                                @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                            @else
                                                                            style="width: 200px; height: 200px;" @endif>
                                                                        @endif
                                                                    @endif

                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div></div>
                                @endif
                                {{-- home about section end --}}

                                {{-- home Our room section start --}}
                                @if ($section['section_name'] == 'Our Room')
                                <div class="tab-pane fade @if(session('tab') and session('tab') == 4) active show @endif" id="our-room-setting" role="tabpanel" aria-labelledby="pills-user-tab-4">
                                    <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                        value="{{ $section['section_name'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                        value="{{ $section['section_slug'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                        value="{{ $section['array_type'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                        value="{{ $section['loop_number'] }}">
                                    @php
                                        $loop = 1;
                                        $section = (array) $section;
                                    @endphp
                                    <div class="col-lg-12">
                                        <div class="card offset-1" style="max-height: 750px;width: 80%;">
                                            @if (
                                                $json_key == 0 ||
                                                    ($json_key - 1 > -1 && $getHotelThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                                    <div class="align-items-center justify-content-between p-4 pb-0">
                                                        <div class="form-check form-switch custom-switch-v1 float-end">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input input-primary"
                                                                name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                                id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                                {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                            @endif
                                            <div class="card-body pt-0">
                                                @php $loop1 = 1; @endphp
                                                @if ($section['array_type'] == 'multi-inner-list')
                                                    @php
                                                        $loop1 = (int) $section['loop_number'];
                                                    @endphp
                                                @endif
                                                @for ($i = 0; $i < $loop1; $i++)
                                                    <div class="row">
                                                        @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                            <?php $field = (array) $field; ?>

                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                value="{{ $field['field_name'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                value="{{ $field['field_slug'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                value="{{ $field['field_help_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                value="{{ $field['field_default_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                value="{{ $field['field_type'] }}">
                                                            @if ($field['field_type'] == 'text')
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <input type="text"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                class="form-control"
                                                                                value="{{ $checked1 }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @else
                                                                            <input type="text" class="form-control"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                value="{{ $field['field_default_text'] }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'text area')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                            
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <textarea name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" id=""
                                                                                class="form-control" rows="5" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                        @else
                                                                            <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" id=""
                                                                                class="form-control" rows="5" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'photo upload')
                                                                <div class="col-md-6">
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        @php
                                                                            $checked2 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']])) {
                                                                                $checked2 = $section[$field['field_slug']][$i];
                                                                            
                                                                                if (is_array($checked2)) {
                                                                                    $checked2 = $checked2['field_prev_text'];
                                                                                }
                                                                            }
                                                                            $imgdisplay = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                value="{{ $checked2 }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if (isset($checked2) && !is_array($checked2))
                                                                            <img src="{{ asset('uploads/' . $checked2) }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @endif
                                                                    @else
                                                                        @php
                                                                            $imgdisplay1 = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]"
                                                                                value="{{ $field['field_default_text'] }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if(file_exists(base_path().'/'.'uploads/'.$field['field_default_text']))
                                                                            <img src="{{ $imgdisplay1 }}/{{ $field['field_default_text'] }} "
                                                                                id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                                class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                                @if (!empty($getHotelThemeSetting['dashboard'])) style=""
                                                                                @else
                                                                                style="width: auto; height: 70px;" @endif
                                                                                            @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                                @else
                                                                                style="width: 200px; height: 200px;" @endif>
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}/{{ $field['field_default_text'] }} "
                                                                                id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                                class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                                @if (!empty($getHotelThemeSetting['dashboard'])) style=""
                                                                                @else
                                                                                style="width: auto; height: 70px;" @endif
                                                                                            @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                                @else
                                                                                style="width: 200px; height: 200px;" @endif>
                                                                        @endif
                                                                    @endif

                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div></div>
                                @endif
                                {{-- home Our rooms section end --}}

                                {{-- home Amenities section start --}}
                                @if ($section['section_name'] == 'Amenities')
                                <div class="tab-pane fade @if(session('tab') and session('tab') == 5) active show @endif" id="amenities-setting" role="tabpanel" aria-labelledby="pills-user-tab-5">
                                    <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                        value="{{ $section['section_name'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                        value="{{ $section['section_slug'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                        value="{{ $section['array_type'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                        value="{{ $section['loop_number'] }}">
                                    @php
                                        $loop = 1;
                                        $section = (array) $section;
                                    @endphp
                                    <div class="col-lg-12">
                                        <div class="card offset-1" style="max-height: 750px;width: 80%;">
                                            @if (
                                                $json_key == 0 ||
                                                    ($json_key - 1 > -1 && $getHotelThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                                    <div class="align-items-center justify-content-between p-4 pb-0">
                                                        <div class="form-check form-switch custom-switch-v1 float-end">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input input-primary"
                                                                name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                                id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                                {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                            @endif
                                            <div class="card-body pt-0">
                                                @php $loop1 = 1; @endphp
                                                @if ($section['array_type'] == 'multi-inner-list')
                                                    @php
                                                        $loop1 = (int) $section['loop_number'];
                                                    @endphp
                                                @endif
                                                @for ($i = 0; $i < $loop1; $i++)
                                                    <div class="row">
                                                        @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                            <?php $field = (array) $field; ?>

                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                value="{{ $field['field_name'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                value="{{ $field['field_slug'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                value="{{ $field['field_help_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                value="{{ $field['field_default_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                value="{{ $field['field_type'] }}">
                                                            @if ($field['field_type'] == 'text')
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <input type="text"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                class="form-control"
                                                                                value="{{ $checked1 }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @else
                                                                            <input type="text" class="form-control"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                value="{{ $field['field_default_text'] }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'text area')
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                            
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <textarea name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" id=""
                                                                                class="form-control" rows="6" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                        @else
                                                                            <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" id=""
                                                                                class="form-control" rows="6" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'photo upload')
                                                                <div class="col-md-6">
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        @php
                                                                            $checked2 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']])) {
                                                                                $checked2 = $section[$field['field_slug']][$i];
                                                                            
                                                                                if (is_array($checked2)) {
                                                                                    $checked2 = $checked2['field_prev_text'];
                                                                                }
                                                                            }
                                                                            $imgdisplay = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                value="{{ $checked2 }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if (isset($checked2) && !is_array($checked2))
                                                                            <img src="{{ asset('uploads/' . $checked2) }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @endif
                                                                    @else
                                                                        @php
                                                                            $imgdisplay = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]"
                                                                                value="{{ $field['field_default_text'] }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }} "
                                                                            id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                            class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                            @if (!empty($getHotelThemeSetting['dashboard'])) style=""
                                                               @else
                                                               style="width: auto; height: 50px;" @endif
                                                                            @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                               @else
                                                               style="width: 200px; height: 200px;" @endif>
                                                                    @endif

                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div></div>
                                @endif
                                {{-- home Amenities section end --}}

                                {{-- home testimonial section start --}}
                                @if ($section['section_name'] == 'Testimonial')
                                <div class="tab-pane fade @if(session('tab') and session('tab') == 6) active show @endif" id="testimonial-setting" role="tabpanel" aria-labelledby="pills-user-tab-6">
                                    <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                        value="{{ $section['section_name'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                        value="{{ $section['section_slug'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                        value="{{ $section['array_type'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                        value="{{ $section['loop_number'] }}">
                                    @php
                                        $loop = 1;
                                        $section = (array) $section;
                                    @endphp
                                    <div class="col-lg-12">
                                        <div class="card offset-1" style="max-height: 2600px;width: 80%;">
                                            @if (
                                                $json_key == 0 ||
                                                    ($json_key - 1 > -1 && $getHotelThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                                    <div class="align-items-center justify-content-between p-4 pb-0">
                                                        <div class="form-check form-switch custom-switch-v1 float-end">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input input-primary"
                                                                name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                                id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                                {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                            @endif
                                            <div class="card-body pt-0">
                                                @php $loop1 = 1; @endphp
                                                @if ($section['array_type'] == 'multi-inner-list')
                                                    @php
                                                        $loop1 = (int) $section['loop_number'];
                                                    @endphp
                                                @endif
                                                @for ($i = 0; $i < $loop1; $i++)
                                                    <div class="row">
                                                        @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                            <?php $field = (array) $field; ?>

                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                value="{{ $field['field_name'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                value="{{ $field['field_slug'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                value="{{ $field['field_help_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                value="{{ $field['field_default_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                value="{{ $field['field_type'] }}">
                                                            @if ($field['field_type'] == 'text')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <input type="text"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                class="form-control"
                                                                                value="{{ $checked1 }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @else
                                                                            <input type="text" class="form-control"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                value="{{ $field['field_default_text'] }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'text area')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                            
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <textarea name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                        @else
                                                                            <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'photo upload')
                                                                <div class="col-md-6">
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        @php
                                                                            $checked2 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']])) {
                                                                                $checked2 = $section[$field['field_slug']][$i];
                                                                            
                                                                                if (is_array($checked2)) {
                                                                                    $checked2 = $checked2['field_prev_text'];
                                                                                }
                                                                            }
                                                                            $imgdisplay = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                value="{{ $checked2 }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if (isset($checked2) && !is_array($checked2))
                                                                            @if(file_exists(base_path().'/'.'uploads/'.$checked2))
                                                                                <img src="{{ asset('uploads/' . $checked2) }}"
                                                                                    style="width: auto; max-height: 80px;">
                                                                            @else
                                                                                <img src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/' . $checked2) }}"
                                                                                    style="width: auto; max-height: 80px;">
                                                                            @endif
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @endif
                                                                    @else
                                                                        @php
                                                                            $imgdisplay = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]"
                                                                                value="{{ $field['field_default_text'] }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }} "
                                                                            id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                            class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                            @if (!empty($getHotelThemeSetting['dashboard'])) style=""
                                                           @else
                                                           style="width: auto; height: 50px;" @endif
                                                                            @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                           @else
                                                           style="width: 200px; height: 200px;" @endif>
                                                                    @endif

                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div></div>
                                @endif
                                {{-- home testimonial section end --}}

                                {{-- home testimonial section start --}}
                                @if ($section['section_name'] == 'Swimming Pool')
                                <div class="tab-pane fade @if(session('tab') and session('tab') == 7) active show @endif" id="swimming-pool-setting" role="tabpanel" aria-labelledby="pills-user-tab-7">
                                    <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                        value="{{ $section['section_name'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                        value="{{ $section['section_slug'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                        value="{{ $section['array_type'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                        value="{{ $section['loop_number'] }}">
                                    @php
                                        $loop = 1;
                                        $section = (array) $section;
                                    @endphp
                                    <div class="col-lg-12">
                                        <div class="card offset-1" style="max-height: 750px;width: 80%;">
                                        @if (
                                            $json_key == 0 ||
                                                ($json_key - 1 > -1 && $getHotelThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                            <div
                                                class="align-items-center justify-content-between p-4 pb-0">
                                                <div class="form-check form-switch custom-switch-v1 float-end">
                                                    <input type="hidden"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        value="off">
                                                    <input type="checkbox" class="form-check-input input-primary"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                        {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        @endif
                                            <div class="card-body pt-0">
                                                @php $loop1 = 1; @endphp
                                                @if ($section['array_type'] == 'multi-inner-list')
                                                    @php
                                                        $loop1 = (int) $section['loop_number'];
                                                    @endphp
                                                @endif
                                                @for ($i = 0; $i < $loop1; $i++)
                                                    <div class="row">
                                                        @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                            <?php $field = (array) $field; ?>

                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                value="{{ $field['field_name'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                value="{{ $field['field_slug'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                value="{{ $field['field_help_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                value="{{ $field['field_default_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                value="{{ $field['field_type'] }}">
                                                            @if ($field['field_type'] == 'text')
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <input type="text"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                class="form-control"
                                                                                value="{{ $checked1 }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @else
                                                                            <input type="text" class="form-control"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                value="{{ $field['field_default_text'] }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'text area')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                            
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <textarea name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" id=""
                                                                                class="form-control" rows="6" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                        @else
                                                                            <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" id=""
                                                                                class="form-control" rows="6" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'photo upload')
                                                                <div class="col-md-6">
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        @php
                                                                            $checked2 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']])) {
                                                                                $checked2 = $section[$field['field_slug']][$i];
                                                                            
                                                                                if (is_array($checked2)) {
                                                                                    $checked2 = $checked2['field_prev_text'];
                                                                                }
                                                                            }
                                                                            $imgdisplay = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                value="{{ $checked2 }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if (isset($checked2) && !is_array($checked2))
                                                                            <img src="{{ asset('uploads/' . $checked2) }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @endif
                                                                    @else
                                                                        @php
                                                                            $imgdisplay1 = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]"
                                                                                value="{{ $field['field_default_text'] }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if(file_exists(base_path().'/'.'uploads/'.$field['field_default_text']))
                                                                            <img src="{{ $imgdisplay1 }}/{{ $field['field_default_text'] }} "
                                                                                id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                                class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                                @if (!empty($getHotelThemeSetting['dashboard'])) style=""
                                                                                @else
                                                                                style="width: auto; height: 70px;" @endif
                                                                                                @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                                @else
                                                                                style="width: 200px; height: 200px;" @endif>
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}/{{ $field['field_default_text'] }} "
                                                                                id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                                class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                                @if (!empty($getHotelThemeSetting['dashboard'])) style=""
                                                                                @else
                                                                                style="width: auto; height: 70px;" @endif
                                                                                                @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                                @else
                                                                                style="width: 200px; height: 200px;" @endif>
                                                                        @endif
                                                                    @endif

                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div></div>
                                @endif
                                {{-- home testimonial section end --}}


                                {{-- home Blog section start --}}
                                @if ($section['section_name'] == 'Offers & News')
                                <div class="tab-pane fade @if(session('tab') and session('tab') == 8) active show @endif" id="offers-news-setting" role="tabpanel" aria-labelledby="pills-user-tab-8">
                                    <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                        value="{{ $section['section_name'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                        value="{{ $section['section_slug'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                        value="{{ $section['array_type'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                        value="{{ $section['loop_number'] }}">
                                    @php
                                        $loop = 1;
                                        $section = (array) $section;
                                    @endphp
                                    <div class="col-lg-12">
                                        <div class="card offset-1" style="max-height: 2400px;width: 80%;">
                                        @if (
                                            $json_key == 0 ||
                                                ($json_key - 1 > -1 && $getHotelThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                            <div
                                                class="align-items-center justify-content-between p-4 pb-0">
                                                <div class="form-check form-switch custom-switch-v1 float-end">
                                                    <input type="hidden"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        value="off">
                                                    <input type="checkbox" class="form-check-input input-primary"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                        {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        @endif
                                            <div class="card-body pt-0">
                                                @php $loop1 = 1; @endphp
                                                @if ($section['array_type'] == 'multi-inner-list')
                                                    @php
                                                        $loop1 = (int) $section['loop_number'];
                                                    @endphp
                                                @endif
                                                @for ($i = 0; $i < $loop1; $i++)
                                                    <div class="row">
                                                        @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                            <?php $field = (array) $field; ?>

                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                value="{{ $field['field_name'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                value="{{ $field['field_slug'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                value="{{ $field['field_help_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                value="{{ $field['field_default_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                value="{{ $field['field_type'] }}">
                                                            @if ($field['field_type'] == 'text')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <input type="text"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                class="form-control"
                                                                                value="{{ $checked1 }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @else
                                                                            <input type="text" class="form-control"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                value="{{ $field['field_default_text'] }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'text area')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                            
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <textarea name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                        @else
                                                                            <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'photo upload')
                                                                <div class="col-md-6">
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        @php
                                                                            $checked2 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']])) {
                                                                                $checked2 = $section[$field['field_slug']][$i];
                                                                            
                                                                                if (is_array($checked2)) {
                                                                                    $checked2 = $checked2['field_prev_text'];
                                                                                }
                                                                            }
                                                                            $imgdisplay = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                value="{{ $checked2 }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if (isset($checked2) && !is_array($checked2))
                                                                            @if(file_exists(base_path().'/'.'uploads/'.$checked2))
                                                                                <img src="{{ asset('uploads/' . $checked2) }}"
                                                                                    style="width: auto; max-height: 80px;">
                                                                            @else
                                                                                <img src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/' . $checked2) }}"
                                                                                    style="width: auto; max-height: 80px;">
                                                                            @endif
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @endif
                                                                    @else
                                                                        @php
                                                                            $imgdisplay = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]"
                                                                                value="{{ $field['field_default_text'] }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }} "
                                                                            id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                            class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                            @if (!empty($getHotelThemeSetting['dashboard'])) style=""
                                                             @else
                                                             style="width: auto; height: 50px;" @endif
                                                                            @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                             @else
                                                             style="width: 200px; height: 200px;" @endif>
                                                                    @endif

                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div></div>
                                @endif
                                {{-- home testimonial section end --}}


                                {{-- social section --}}
                                @if ($section['section_name'] == 'Instagram' || $section['section_name'] == 'Quote')
                                <div class="tab-pane fade @if(session('tab') and session('tab') == 9) active show @endif" id="instagram-setting" role="tabpanel" aria-labelledby="pills-user-tab-9">
                                    <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                        value="{{ $section['section_name'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                        value="{{ $section['section_slug'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                        value="{{ $section['array_type'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                        value="{{ $section['loop_number'] }}">
                                    @php
                                        $loop = 1;
                                        $section = (array) $section;
                                    @endphp
                                    <div class="col-lg-12">
                                        <div class=" card offset-1" style="max-height: 750px;width: 80%;">
                                        @if (
                                            $json_key == 0 ||
                                                ($json_key - 1 > -1 && $getHotelThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                            <div
                                                class="align-items-center justify-content-between p-4 pb-0">
                                                <div class="form-check form-switch custom-switch-v1 float-end">
                                                    <input type="hidden"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        value="off">
                                                    <input type="checkbox" class="form-check-input input-primary"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                        {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        @endif
                                            <div class="card-body pt-0">
                                                @php $loop1 = 1; @endphp
                                                @if ($section['array_type'] == 'multi-inner-list')
                                                    @php
                                                        $loop1 = (int) $section['loop_number'];
                                                    @endphp
                                                @endif
                                                @for ($i = 0; $i < $loop1; $i++)
                                                    <div class="row">
                                                        @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                            <?php $field = (array) $field; ?>

                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                value="{{ $field['field_name'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                value="{{ $field['field_slug'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                value="{{ $field['field_help_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                value="{{ $field['field_default_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                value="{{ $field['field_type'] }}">
                                                            @if ($field['field_type'] == 'text')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <input type="text"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                class="form-control"
                                                                                value="{{ $checked1 }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @else
                                                                            <input type="text" class="form-control"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                value="{{ $field['field_default_text'] }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'text area')
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                            
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <textarea name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                        @else
                                                                            <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                id="" class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'photo upload')
                                                                <div class="col-md-6">
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        @php
                                                                            $checked2 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']])) {
                                                                                $checked2 = $section[$field['field_slug']][$i];
                                                                            
                                                                                if (is_array($checked2)) {
                                                                                    $checked2 = $checked2['field_prev_text'];
                                                                                }
                                                                            }
                                                                            $imgdisplay = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                value="{{ $checked2 }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if (isset($checked2) && !is_array($checked2))
                                                                            @if(file_exists(base_path().'/'.'uploads/'.$checked2))
                                                                                <img src="{{ asset('uploads/' . $checked2) }}"
                                                                                    style="width: auto; max-height: 80px;">
                                                                            @else
                                                                                <img src="{{ asset('uploads/' . $checked2) }}"
                                                                                    style="width: auto; max-height: 80px;">
                                                                            @endif
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @endif
                                                                    @else
                                                                        @php
                                                                            $imgdisplay = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]"
                                                                                value="{{ $field['field_default_text'] }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }} "
                                                                            id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                            class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                            @if (!empty($getHotelThemeSetting['dashboard'])) style=""
                                                        @else
                                                        style="width: auto; height: 50px;" @endif
                                                                            @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                        @else
                                                        style="width: 200px; height: 200px;" @endif>
                                                                    @endif

                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'multi file upload')
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        <input type="file"
                                                                            name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][multi_image][]"
                                                                            class="form-control custom-input-file"
                                                                            multiple>
                                                                    </div>
                                                                </div>
                                                                <div id="img-count"
                                                                    class="badge badge-primary rounded-pill">
                                                                </div>
                                                                @if (!empty($field['image_path']))
                                                                    @foreach ($field['image_path'] as $key => $file_pathh)
                                                                        <div class="card mb-3 border shadow-none product_Image"
                                                                            data-value="{{ $file_pathh }}" style="min-height: 0px;">
                                                                            <div class="px-3 py-3">
                                                                                <div class="row align-items-center">
                                                                                    <div class="col ml-n2">
                                                                                        <p
                                                                                            class="card-text small text-muted">
                                                                                            <input type="hidden"
                                                                                                name='array[{{ $json_key }}][prev_image][]'
                                                                                                value="{{ $file_pathh }}">
                                                                                            <img class="rounded"
                                                                                                src="{{ asset('uploads/' . $file_pathh) }}"
                                                                                                width="70px"
                                                                                                alt="Image placeholder"
                                                                                                data-dz-thumbnail>

                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="col-auto actions">
                                                                                        <a class="action-item btn btn-sm btn-icon btn-light-secondary"
                                                                                            href=" {{ asset('uploads/' . $file_pathh) }}"
                                                                                            download=""
                                                                                            data-toggle="tooltip"
                                                                                            data-original-title="{{ __('Download') }}">
                                                                                            <i
                                                                                                data-feather="download"></i>
                                                                                        </a>
                                                                                    </div>
                                                                                    <div class="col-auto actions">
                                                                                        {{ Form::open(['class'=>'mb-0']) }}
                                                                                        {{ Form::close() }}
                                                                                        {{ Form::open(['method' => 'GET', 'route' => ['hotel.hoteldeletethemeimage', [$hotel->slug, $theme, $key]],'id'=>'delete-form-'.$key,'class'=>'mb-0']) }}
                                                                                        <a href="#" name="deleteRecord"
                                                                                            class="action-item deleteRecord btn btn-sm bg-icon btn-light-secondary me-2 bs-pass-para show_confirm"
                                                                                            data-bs-toggle="tooltip" title="Delete"
                                                                                            data-bs-original-title="Delete Booking" aria-label="Delete"
                                                                                            data-confirm-yes="delete-form-{{ $key }}">
                                                                                            <i data-feather="trash-2"></i>
                                                                                        </a>
                                                                                        {{ Form::close() }}
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        
                    </div>
                </div>
            </div>
                        
                          <div class="col-12 text-lg-end offset-1" style="width:80%;">
                                <button type="submit" class="btn btn-primary submit_all"> <i data-feather="check-circle"
                                        class="me-2"></i>{{ __('Save Changes') }}</button>
                        </div>

                    </div></div>
                    <div class="tab-pane fade" id="pills-footer" role="tabpanel" aria-labelledby="pills-footer">
                            
                            <div class="page-header pt-3 pb-4">
                                <div class="page-block">
                                    <div class="row gy-4 align-items-center">
                                        <div class="col-md-6">
                                            <ul class="nav nav-pills nav-fill information-tab" id="pills-tab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link @if(!session('tab') or (session('tab') and session('tab') == 1)) active @endif" id="footer1-setting-tab" data-bs-toggle="pill"
                                                        data-bs-target="#footer1-setting" type="button">{{__('Home-Footer-1')}}</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link @if(session('tab') and session('tab') == 2) active @endif" id="footer2-setting-tab" data-bs-toggle="pill"
                                                        data-bs-target="#footer2-setting" type="button">{{__('Home-Footer-2')}}</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            

<div class="row">
    <div class="col-12">
        <div class="tab-content" id="pills-tabContent">

                            <div class="tab-pane fade @if(!session('tab') or (session('tab') and session('tab') == 1)) active show @endif" id="footer1-setting" role="tabpanel" aria-labelledby="pills-user-tab-1">
                                <div class="card offset-1" style="max-height: 3100px;width: 80%;">
                                @foreach ($getHotelThemeSetting as $json_key => $section)
                                    @php
                                        $id = '';
                                        
                                        if ($section['section_name'] == 'Home-Brand-Logo') {
                                            $id = 'Brand_Logo';
                                        }
                                        if ($section['section_name'] == 'Header') {
                                            $id = 'Header_Setting';
                                            $class = 'card';
                                        }
                                        if ($section['section_name'] == 'Home-Promotions') {
                                            $id = 'Features_Setting';
                                        }
                                        if ($section['section_name'] == 'Home-Email-Subscriber') {
                                            $id = 'Email_Subscriber_Setting';
                                        }
                                        if ($section['section_name'] == 'Home-Categories') {
                                            $id = 'Categories';
                                        }
                                        if ($section['section_name'] == 'Home-Testimonial') {
                                            $id = 'Testimonials';
                                        }
                                        if ($section['section_name'] == 'Home-Footer-1') {
                                            $id = 'Footer_1';
                                        }
                                        if ($section['section_name'] == 'Home-Footer-2') {
                                            $id = 'Footer_2';
                                        }
                                        if ($section['section_name'] == 'Banner-Image') {
                                            $id = 'Banner_Img_Setting';
                                        }
                                        if ($section['section_name'] == 'Quote') {
                                            $id = 'Quote';
                                        }
                                        if ($section['section_name'] == 'Top-Purchased') {
                                            $id = 'top_purchased';
                                        }
                                        if ($section['section_name'] == 'Product-Section-Header') {
                                            $id = 'product_header';
                                        }
                                        if ($section['section_name'] == 'Latest Product') {
                                            $id = 'latest_product';
                                        }
                                        if ($section['section_name'] == 'Central-Banner') {
                                            $id = 'Banner_Setting';
                                        }
                                        if ($section['section_name'] == 'Latest-Category') {
                                            $id = 'latest_categories';
                                        }
                                        if ($section['section_name'] == 'Latest-Products') {
                                            $id = 'latest_Products';
                                        }
                                        
                                    @endphp
                                    @if ($section['section_name'] == 'Home-Footer-1')
                                        @if (
                                            $json_key == 0 ||
                                                ($json_key - 1 > -1 && $getHotelThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                            <div
                                                class="align-items-center justify-content-between p-4 pb-0">
                                                <div class="form-check form-switch custom-switch-v1 float-end">
                                                    <input type="hidden"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        value="off">
                                                    <input type="checkbox" class="form-check-input input-primary"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                        {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                                    @foreach ($getHotelThemeSetting as $json_key => $section)
                                        @if ($section['section_name'] == 'Home-Footer-1')
                                            <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                                value="{{ $section['section_name'] }}">
                                            <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                                value="{{ $section['section_slug'] }}">
                                            <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                                value="{{ $section['array_type'] }}">
                                            <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                                value="{{ $section['loop_number'] }}">
                                            @php
                                                $loop = 1;
                                                $section = (array) $section;
                                            @endphp
                                            <div class="card-body pt-0">
                                                @php $loop1 = 1; @endphp
                                                @if ($section['array_type'] == 'multi-inner-list')
                                                    @php
                                                        $loop1 = (int) $section['loop_number'];
                                                    @endphp
                                                @endif
                                                @for ($i = 0; $i < $loop1; $i++)
                                                    <div class="row">
                                                        @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                            <?php $field = (array) $field; ?>
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                value="{{ $field['field_name'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                value="{{ $field['field_slug'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                value="{{ $field['field_help_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                value="{{ $field['field_default_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                value="{{ $field['field_type'] }}">
                                                            @if ($field['field_type'] == 'text')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <input type="text"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                class="form-control"
                                                                                value="{{ $checked1 }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @else
                                                                            <input type="text" class="form-control"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                value="{{ $field['field_default_text'] }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'text area')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                            
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <textarea name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                        @else
                                                                            <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                id="" class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'checkbox')
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="form-check form-switch custom-switch-v1">
                                                                            <label class="form-check-label"
                                                                                for="array[ {{ $section['section_slug'] }}][{{ $field['field_slug'] }}]">{{ $field['field_name'] }}</label>
                                                                            @if ($section['array_type'] == 'multi-inner-list')
                                                                                @php
                                                                                    $checked1 = '';
                                                                                    
                                                                                    if (!empty($section[$field['field_slug']][$i]) && $section[$field['field_slug']][$i] == 'on') {
                                                                                        $checked1 = 'checked';
                                                                                    } else {
                                                                                        if (!empty($section['section_enable']) && $section['section_enable'] == 'on') {
                                                                                            $checked1 = 'checked';
                                                                                        }
                                                                                    }
                                                                                    
                                                                                @endphp
                                                                                <input type="hidden"
                                                                                    name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                    value="off">
                                                                                <input type="checkbox"
                                                                                    class="form-check-input input-primary"
                                                                                    name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                    id="array[{{ $section['section_slug'] }}][{{ $field['field_slug'] }}]"
                                                                                    {{ $checked1 }}>
                                                                            @else
                                                                                @php
                                                                                    $checked = '';
                                                                                    if (!empty($field['field_default_text']) && $field['field_default_text'] == 'on') {
                                                                                        $checked = 'checked';
                                                                                    }
                                                                                @endphp
                                                                                <input type="hidden"
                                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                    value="off">
                                                                                <input type="checkbox"
                                                                                    class="form-check-input input-primary"
                                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                    id="array[{{ $section['section_slug'] }}][{{ $field['field_slug'] }}]"
                                                                                    {{ $checked }}>
                                                                            @endif

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'photo upload')
                                                                <div class="col-md-6">
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        @php
                                                                            $checked2 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']])) {
                                                                                $checked2 = $section[$field['field_slug']][$i];
                                                                            
                                                                                if (is_array($checked2)) {
                                                                                    $checked2 = $checked2['field_prev_text'];
                                                                                }
                                                                            }
                                                                            $imgdisplay = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                value="{{ $checked2 }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if (isset($checked2) && !is_array($checked2))
                                                                            <img src="{{ asset('uploads/' . $checked2) }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @endif
                                                                    @else
                                                                        @php
                                                                            $imgdisplay = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]"
                                                                                value="{{ $field['field_default_text'] }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }} "
                                                                            id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                            class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                            @if (!empty($getHotelThemeSetting['dashboard'])) style=""
                                                                        @else
                                                                        style="width: auto; height: 50px;" @endif
                                                                            @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                        @else
                                                                        style="width: 200px; height: 200px;" @endif>
                                                                    @endif

                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endfor
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                </div>
                            <div class="tab-pane fade @if(session('tab') and session('tab') == 2) active show @endif" id="footer2-setting" role="tabpanel" aria-labelledby="pills-user-tab-2">
                                <div class="card offset-1" style="max-height: 1500px;width: 80%;">
                                @foreach ($getHotelThemeSetting as $json_key => $section)
                                    @php
                                        $id = '';
                                        
                                        if ($section['section_name'] == 'Home-Brand-Logo') {
                                            $id = 'Brand_Logo';
                                        }
                                        if ($section['section_name'] == 'Header') {
                                            $id = 'Header_Setting';
                                            $class = 'card';
                                        }
                                        if ($section['section_name'] == 'Home-Promotions') {
                                            $id = 'Features_Setting';
                                        }
                                        if ($section['section_name'] == 'Home-Email-Subscriber') {
                                            $id = 'Email_Subscriber_Setting';
                                        }
                                        if ($section['section_name'] == 'Home-Categories') {
                                            $id = 'Categories';
                                        }
                                        if ($section['section_name'] == 'Home-Testimonial') {
                                            $id = 'Testimonials';
                                        }
                                        if ($section['section_name'] == 'Home-Footer-1') {
                                            $id = 'Footer_1';
                                        }
                                        if ($section['section_name'] == 'Home-Footer-2') {
                                            $id = 'Footer_2';
                                        }
                                        if ($section['section_name'] == 'Banner-Image') {
                                            $id = 'Banner_Img_Setting';
                                        }
                                        if ($section['section_name'] == 'Quote') {
                                            $id = 'Quote';
                                        }
                                        if ($section['section_name'] == 'Top-Purchased') {
                                            $id = 'top_purchased';
                                        }
                                        if ($section['section_name'] == 'Product-Section-Header') {
                                            $id = 'product_header';
                                        }
                                        if ($section['section_name'] == 'Latest Product') {
                                            $id = 'latest_product';
                                        }
                                        if ($section['section_name'] == 'Central-Banner') {
                                            $id = 'Banner_Setting';
                                        }
                                        if ($section['section_name'] == 'Latest-Category') {
                                            $id = 'latest_categories';
                                        }
                                        if ($section['section_name'] == 'Latest-Products') {
                                            $id = 'latest_Products';
                                        }
                                        
                                    @endphp
                                    @if ($section['section_name'] == 'Home-Footer-2')
                                        @if (
                                            $json_key == 0 ||
                                                ($json_key - 1 > -1 && $getHotelThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                            <div
                                                class="align-items-center justify-content-between p-4 pb-0">
                                                <div class="form-check form-switch custom-switch-v1 float-end">
                                                    <input type="hidden"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        value="off">
                                                    <input type="checkbox" class="form-check-input input-primary"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                        {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                                    @foreach ($getHotelThemeSetting as $json_key => $section)
                                        @if ($section['section_name'] == 'Home-Footer-2')
                                            <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                                value="{{ $section['section_name'] }}">
                                            <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                                value="{{ $section['section_slug'] }}">
                                            <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                                value="{{ $section['array_type'] }}">
                                            <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                                value="{{ $section['loop_number'] }}">
                                            @php
                                                $loop = 1;
                                                $section = (array) $section;
                                            @endphp
                                            <div class="card-body pt-0">
                                                @php $loop1 = 1; @endphp
                                                @if ($section['array_type'] == 'multi-inner-list')
                                                    @php
                                                        $loop1 = (int) $section['loop_number'];
                                                    @endphp
                                                @endif
                                                @for ($i = 0; $i < $loop1; $i++)
                                                    <div class="row">
                                                        @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                            <?php $field = (array) $field; ?>
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                value="{{ $field['field_name'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                value="{{ $field['field_slug'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                value="{{ $field['field_help_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                value="{{ $field['field_default_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                value="{{ $field['field_type'] }}">
                                                            @if ($field['field_type'] == 'text')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <input type="text"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                class="form-control"
                                                                                value="{{ $checked1 }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @else
                                                                            <input type="text" class="form-control"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                value="{{ $field['field_default_text'] }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'text area')
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                            
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <textarea name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                        @else
                                                                            <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                id="" class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'photo upload')
                                                                <div class="col-md-6">
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        @php
                                                                            $checked2 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']])) {
                                                                                $checked2 = $section[$field['field_slug']][$i];
                                                                            
                                                                                if (is_array($checked2)) {
                                                                                    $checked2 = $checked2['field_prev_text'];
                                                                                }
                                                                            }
                                                                            $imgdisplay = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                value="{{ $checked2 }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if (isset($checked2) && !is_array($checked2))
                                                                            <img src="{{ asset('uploads/' . $checked2) }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @endif
                                                                    @else
                                                                        @php
                                                                            $imgdisplay = get_file('uploads/');
                                                                            $imgdisplay = get_file('packages/workdo/Holidayz/src/Resources/assets/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]"
                                                                                value="{{ $field['field_default_text'] }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }} "
                                                                            id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                            class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                            @if (!empty($getHotelThemeSetting['dashboard'])) style=""
                                                                        @else
                                                                        style="width: auto; height: 50px;" @endif
                                                                            @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                        @else
                                                                        style="width: 200px; height: 200px;" @endif>
                                                                    @endif

                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endfor
                                            </div>
                                        @endif
                                    @endforeach
                            </div>
                            </div>
                        </div>
                        </div>

                          <div class="col-12 text-lg-end offset-1" style="width:80%;">
                                    <button type="submit" class="btn mb-3  btn-primary submit_all"> <i
                                            data-feather="check-circle"
                                            class="me-2"></i>{{ __('Save Changes') }}</button>
                            </div>
                    </div>
                        </div>
            </div>
            {!! Form::close() !!}
        @endif
    </div>
@endsection
