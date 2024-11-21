@extends('layouts.main')
@section('page-title', __('Manage Coupon Details'))

@section('page-breadcrumb')
    {{ __('Coupon') }},
    {{__('Coupon Details')}}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('User')}}</th>
                                <th> {{__('Date')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($userCoupons as $userCoupon)
                                <tr class="font-style">
                                    <td>{{ !empty($userCoupon->userDetail)?$userCoupon->userDetail->name: 'Guest' }}</td>
                                    <td>{{ $userCoupon->created_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
