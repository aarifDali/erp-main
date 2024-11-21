{{ Form::model($bookings, ['route' => ['mainbookingorder.update', $bookings->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data','class'=>'needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        @php
            $paymentStatus = ['0' => 'UnPaid', '1' => 'Paid'];
        @endphp
        @php
            $payments = \Workdo\Holidayz\Entities\Utility::ActivePaymentGateway();
        @endphp
        <div class="form-group col-md-6">
            {!! Form::label('', __('Payment Method'), ['class' => 'form-label']) !!}
            <select class="form-control" name="payment_method" id="payment_method">
                @foreach($payments as $value => $payment)
                    <option value="{{ $payment }}" {{strtolower($bookings->payment_method) == strtolower($payment) ? 'selected' : ''}}>{{ $payment }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('', __('Payment Status'), ['class' => 'form-label']) !!}
            {!! Form::select('payment_status', $paymentStatus, null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('', __('Total Rent'), ['class' => 'form-label']) !!}
            {!! Form::number('total', $bookings->amount_to_pay , ['class' => 'form-control total','disabled' => true]) !!}
        </div>
        <div class="modal-footer pb-0">
            <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
            <input type="submit" value="Update" class="btn btn-primary bg-primary">
        </div>
    </div>
</div>
{!! Form::close() !!}
