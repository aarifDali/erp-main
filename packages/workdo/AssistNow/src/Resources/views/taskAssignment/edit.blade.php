@extends('layouts.main')
@section('page-title')
    {{ __('Edit Task Assignment') }}
@endsection
@section('page-breadcrumb')
    {{ __('Task Assignment') }}
@endsection

@section('content')
<div class="row">
    {{ Form::open(['route' => ['task-assignments.update', $task->id], 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}

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
                                    <option value="{{ $employee->id }}" {{ $task->employee_id == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
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
                                    <option value="{{ $client->id }}" {{ $task->client_id == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
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
                                    <option value="{{ $service->id }}" {{ $task->service_id == $service->id ? 'selected' : '' }} data-billing-interval="{{ $service->billing_interval }}">
                                        {{ $service->name }} (Billing Interval: {{ $service->billing_interval }} Mins)
                                    </option>
                                @endforeach
                            </select>                            
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            {!! Form::label('', __('Fund Recieved'), ['class' => 'form-label']) !!}<x-required></x-required>
                            {!! Form::number('fund_recieved',$task->fund_recieved, ['class' => 'form-control', 'placeholder' => 'Enter Fund recieved from the Government','required'=> true, 'id' => 'fund_recieved', 'step' => '0.01']) !!}
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            {{ Form::label('service_date', __('Date'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::date('service_date', $task->service_date, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Date']) }}
                        </div>
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            {!! Form::label('', __('Time Spent'), ['class' => 'form-label']) !!}<x-required></x-required>
                            {!! Form::number('time_spent',$task->time_spent, ['class' => 'form-control', 'placeholder' => 'Enter Time Spend (In Mins)','required'=> true, 'id' => 'time_spent']) !!}
                        </div>
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            {!! Form::label('', __('Service Charge'), ['class' => 'form-label']) !!}<x-required></x-required>
                            {!! Form::number('service_charge',$task->service_charge, ['class' => 'form-control', 'placeholder' => 'Enter Service Charge for the Billing Interval','required'=> true, 'id' => 'service_charge', 'step' => '0.01']) !!}
                        </div>
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            {!! Form::label('total_charge', __('Total Charge'), ['class' => 'form-label']) !!}<x-required></x-required>
                            {!! Form::number('total_charge', $task->total_charge, ['class' => 'form-control', 'readonly' => true, 'id' => 'total_charge', 'step' => '0.01']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{ __('Cancel') }}"
            onclick="location.href = '{{ route('task-assignments.index') }}';" class="btn btn-light me-2">
        <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary" id="submit">
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
                
                if (!selectedService) {
                    console.error("Service dropdown not found!");
                    return;
                }

                let billingInterval = parseFloat(selectedService.options[selectedService.selectedIndex]?.getAttribute('data-billing-interval')) || 1;

                // Ensure billingInterval is not zero to prevent division errors
                if (billingInterval <= 0) {
                    console.error("Invalid billing interval: ", billingInterval);
                    billingInterval = 1; // Default to 1 to prevent calculation issues
                }

                let totalCharge = (timeSpent * serviceCharge) / billingInterval;

                document.getElementById('total_charge').value = totalCharge.toFixed(2);
            }

            // Attach event listeners
            document.getElementById('time_spent').addEventListener('input', calculateTotalCharge);
            document.getElementById('service_charge').addEventListener('input', calculateTotalCharge);
            document.getElementById('service_id').addEventListener('change', calculateTotalCharge);
        });
    </script>
@endpush


