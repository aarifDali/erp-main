@extends('layouts.main')
@section('page-title')
    {{ __('Create Task Assignment') }}
@endsection
@section('page-breadcrumb')
    {{ __('Task Assignment') }}
@endsection

@section('content')
<div class="row">
    {{ Form::open(['route' => 'task-assignments.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            {{ Form::label('employee_id', __('Employee'), ['class' => 'form-label']) }}<x-required></x-required>
                            <select class="form-control" name="employee_id" id="employee_id" required>
                                <option value="" data-rent-value="0">{{ __('Select Employee') }}</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            {{ Form::label('client_id', __('Client'), ['class' => 'form-label']) }}<x-required></x-required>
                            <select class="form-control" name="client_id" id="client_id" required>
                                <option value="" data-rent-value="0">{{ __('Select Client') }}</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            {{ Form::label('service_id', __('Service'), ['class' => 'form-label']) }}<x-required></x-required>
                            <select class="form-control" name="service_id" id="service_id" required>
                                <option value="" data-billing-interval="0">{{ __('Select Service') }}</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}" data-billing-interval="{{ $service->billing_interval }}">
                                        {{ $service->name }} (Billing Interval: {{ $service->billing_interval }} Mins)
                                    </option>
                                @endforeach
                            </select>                            
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            {!! Form::label('', __('Fund Recieved'), ['class' => 'form-label']) !!}<x-required></x-required>
                            {!! Form::number('fund_recieved', null, ['class' => 'form-control', 'placeholder' => 'Enter Fund recieved from the Government','required'=> true, 'id' => 'fund_recieved', 'step' => '0.01']) !!}
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            {{ Form::label('service_date', __('Date'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::date('service_date', date('Y-m-d'), ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Date']) }}
                        </div>
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            {!! Form::label('', __('Time Spent'), ['class' => 'form-label']) !!}<x-required></x-required>
                            {!! Form::number('time_spent', null, ['class' => 'form-control', 'placeholder' => 'Enter Time Spend (In Mins)','required'=> true, 'id' => 'time_spent']) !!}
                        </div>
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            {!! Form::label('', __('Service Charge'), ['class' => 'form-label']) !!}<x-required></x-required>
                            {!! Form::number('service_charge', null, ['class' => 'form-control', 'placeholder' => 'Enter Service Charge for the Billing Interval','required'=> true, 'id' => 'service_charge', 'step' => '0.01']) !!}
                        </div>
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            {!! Form::label('total_charge', __('Total Charge'), ['class' => 'form-label']) !!}<x-required></x-required>
                            {!! Form::number('total_charge', null, ['class' => 'form-control', 'readonly' => true, 'id' => 'total_charge', 'step' => '0.01']) !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{ __('Cancel') }}"
            onclick="location.href = '{{ route('task-assignments.index') }}';" class="btn btn-light me-2">
        <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary" id="submit">
    </div>
    {{ Form::close() }}
</div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function calculateTotalCharge() {
                let timeSpent = parseFloat(document.getElementById('time_spent').value) || 0;
                let serviceCharge = parseFloat(document.getElementById('service_charge').value) || 0;
                let selectedService = document.getElementById('service_id');
                
                let billingInterval = parseFloat(selectedService.options[selectedService.selectedIndex].getAttribute('data-billing-interval')) || 1;

                if (timeSpent > 0 && serviceCharge > 0 && billingInterval > 0) {
                    let totalCharge = (timeSpent / billingInterval) * serviceCharge;
                    document.getElementById('total_charge').value = totalCharge.toFixed(2);
                } else {
                    document.getElementById('total_charge').value = '';
                }
            }

            document.getElementById('time_spent').addEventListener('input', calculateTotalCharge);
            document.getElementById('service_charge').addEventListener('input', calculateTotalCharge);
            document.getElementById('service_id').addEventListener('change', calculateTotalCharge);
        });
    </script>
@endpush
