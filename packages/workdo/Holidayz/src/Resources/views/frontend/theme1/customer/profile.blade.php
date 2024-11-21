@extends('holidayz::frontend.layouts.theme1')
@section('page-title')
    {{__('Profile')}}
@endsection
@section('content')
    @php
        $path1 = get_file('uploads/users-avatar');
        $path = get_file('uploads/hotel-customer-avatar');
        $imagePath = auth()
            ->guard('holiday')
            ->user()->avatar
            ? $path . '/' . auth()
                ->guard('holiday')
                ->user()->avatar
            : $path1 . '/' . 'avatar.png';
    @endphp

    <style>
        .profile-img-div {
            display: flex;
            justify-content: center;
        }

        .profile-img {
            height: 100px;
            width: 100px;
        }

        .rounded-circle {
            border-radius: 50% !important;
        }

        .is-invalid {
            border-color: red;
        }
        .invalid-feedback{
            color: red;
        }
    </style>
    <!--wrapper start here-->
    <div class="wrapper" style="padding-block: 100px">
        <section class="login-section padding-bottom">
            <div class="offset-left">
                <div class="row no-gutters">
                    <div class="col-lg-5 col-12">
                        <div class="login-left">
                            <form action="{{ route('customer.profile.update',$slug) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="section-title">
                                    <h3>{{ __('Profile') }}</h3>
                                </div>
                                <div class="create-account">
                                    <div class="profile-img-div">
                                        <img src="{{ $imagePath }}" class="profile-img rounded-circle">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Avtar') }}:</label>
                                        <input type="file" class="form-control" name="avatar">
                                        @error('avatar')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('First Name') }}:</label>
                                        <input type="text" class="form-control" name="name"
                                            placeholder="{{__('Please Enter Your First Name') }}" required=""
                                            value="{{ auth()->guard('holiday')->user()->name }}">
                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Last Name') }}:</label>
                                        <input type="text" class="form-control" name="last_name"
                                            placeholder="{{__('Please Enter Your Last Name') }}"
                                            value="{{ auth()->guard('holiday')->user()->last_name }}">
                                            @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Email') }}:</label>
                                        <input type="email" class="form-control" name="email"
                                            placeholder="{{__('Please Enter Your Email Address') }}" required=""
                                            value="{{ auth()->guard('holiday')->user()->email }}">
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Dob') }}:</label>
                                        <input type="date" class="form-control" name="dob"
                                            placeholder="{{__('Please Select Dob') }}"
                                            value="{{ auth()->guard('holiday')->user()->dob }}">
                                            @error('dob')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Mobile Number') }}:</label>
                                        <input type="number" class="form-control" name="mobile_phone"
                                            placeholder="{{__('Please Enter Your Mobile Number')}}"
                                            value="{{ auth()->guard('holiday')->user()->mobile_phone }}">
                                            <div class="text-xs" style="color: red; font-size: 11px">{{ __('Please add mobile no with country code. (ex. +91)') }}</div>
                                            @error('mobile_phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <button class="btn d-flex align-items-center login-btn">
                                        <span>{{ __('Save') }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                            viewBox="0 0 19 19" fill="none">
                                            <path
                                                d="M9.54395 1.31787C5.09795 1.31787 1.48145 4.93437 1.48145 9.38037C1.48145 13.8264 5.09795 17.4429 9.54395 17.4429C13.9899 17.4429 17.6064 13.8264 17.6064 9.38037C17.6064 4.93437 13.9899 1.31787 9.54395 1.31787ZM9.54395 16.3179C5.7182 16.3179 2.60645 13.2061 2.60645 9.38037C2.60645 5.55462 5.7182 2.44287 9.54395 2.44287C13.3697 2.44287 16.4814 5.55462 16.4814 9.38037C16.4814 13.2061 13.3697 16.3179 9.54395 16.3179ZM13.0629 9.5957C13.0344 9.6647 12.9932 9.72688 12.9415 9.77863L10.6915 12.0286C10.582 12.1381 10.4379 12.1936 10.2939 12.1936C10.1499 12.1936 10.0059 12.1389 9.89642 12.0286C9.67667 11.8089 9.67667 11.4526 9.89642 11.2328L11.1864 9.94287H6.54395C6.23345 9.94287 5.98145 9.69087 5.98145 9.38037C5.98145 9.06987 6.23345 8.81787 6.54395 8.81787H11.1857L9.89569 7.52789C9.67594 7.30814 9.67594 6.95187 9.89569 6.73212C10.1154 6.51237 10.4717 6.51237 10.6915 6.73212L12.9415 8.98212C12.9932 9.03387 13.0344 9.09604 13.0629 9.16504C13.1199 9.30304 13.1199 9.4577 13.0629 9.5957Z"
                                                fill="black" />
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-7 col-12">
                        <div class="login-right">
                            <form action="{{ route('customer.password.update', $slug) }}" method="POST">
                                @csrf
                                <div class="create-account create-account-right">
                                    <h4 class="h3">{{ __('Password') }}</h4>
                                    <div class="input-form">
                                        <div class="form-group">
                                            <label>{{ __('Old Password') }} :</label>
                                            <input type="password" id="old_password" name="old_password" placeholder="{{__('Please Enter Your Old Password')}}"
                                                class="form-control @error('old_password') is-invalid @enderror"
                                                required="">
                                            @error('old_password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('New Password') }}:</label>
                                            <input id="password" type="password" data-indicator="pwindicator" placeholder="{{__('Please Enter Your New Passeord')}}"
                                                class="form-control pwstrength @error('password') is-invalid @enderror"
                                                name="password" required autocomplete="new-password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('Password Confirmation') }}:</label>
                                            <input id="password_confirmation" type="password"
                                                data-indicator="password_confirmation" placeholder="{{__('Please Enter Your Confirm Password')}}"
                                                class="form-control pwstrength @error('password_confirmation') is-invalid @enderror"
                                                name="password_confirmation" required autocomplete="new-password">
                                            @error('password_confirmation')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                    </div>
                                    <button class="btn d-flex align-items-center login-btn">
                                        <span>{{ __('Update') }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                            viewBox="0 0 19 19" fill="none">
                                            <path
                                                d="M9.54395 1.31787C5.09795 1.31787 1.48145 4.93437 1.48145 9.38037C1.48145 13.8264 5.09795 17.4429 9.54395 17.4429C13.9899 17.4429 17.6064 13.8264 17.6064 9.38037C17.6064 4.93437 13.9899 1.31787 9.54395 1.31787ZM9.54395 16.3179C5.7182 16.3179 2.60645 13.2061 2.60645 9.38037C2.60645 5.55462 5.7182 2.44287 9.54395 2.44287C13.3697 2.44287 16.4814 5.55462 16.4814 9.38037C16.4814 13.2061 13.3697 16.3179 9.54395 16.3179ZM13.0629 9.5957C13.0344 9.6647 12.9932 9.72688 12.9415 9.77863L10.6915 12.0286C10.582 12.1381 10.4379 12.1936 10.2939 12.1936C10.1499 12.1936 10.0059 12.1389 9.89642 12.0286C9.67667 11.8089 9.67667 11.4526 9.89642 11.2328L11.1864 9.94287H6.54395C6.23345 9.94287 5.98145 9.69087 5.98145 9.38037C5.98145 9.06987 6.23345 8.81787 6.54395 8.81787H11.1857L9.89569 7.52789C9.67594 7.30814 9.67594 6.95187 9.89569 6.73212C10.1154 6.51237 10.4717 6.51237 10.6915 6.73212L12.9415 8.98212C12.9932 9.03387 13.0344 9.09604 13.0629 9.16504C13.1199 9.30304 13.1199 9.4577 13.0629 9.5957Z"
                                                fill="black" />
                                        </svg>
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!---wrapper end here-->
@endsection
