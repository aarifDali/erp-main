@extends('holidayz::frontend.layouts.theme1')
@section('page-title')
    {{ ucfirst($pagedetails->name) }}
@endsection
@section('content')
<div class="wrapper" style="margin-top: 70.5966px;">
        <section class="price-summery checkout-page padding-bottom">
            <div class="price-summery-title checkout-title border-bottom">
                <div class="container">
                    <div class="section-title text-center">
                        <h2>{{ $pagedetails->name }}</h2>
                    </div>
                </div>
            </div>
            <div class="price-summery-detl checkout-info">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-12">
                            {!! $pagedetails->contents   !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
