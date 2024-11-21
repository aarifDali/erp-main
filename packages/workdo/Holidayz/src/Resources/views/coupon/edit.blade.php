{{Form::model($coupon, array('route' => array('room-booking-coupon.update', $coupon->id), 'method' => 'PUT','class'=>'needs-validation','novalidate')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{Form::label('name',__('Name'),['class'=>'form-label'])}}<x-required></x-required>
            {{Form::text('name',null,array('class'=>'form-control font-style', 'placeholder' => 'Enter Coupon Name','required'=>'required'))}}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('discount',__('Discount'),['class'=>'form-label'])}}<x-required></x-required>
            {{Form::number('discount',null,array('class'=>'form-control', 'placeholder' => 'Enter Discount','required'=>'required','step'=>'0.01','max' => '100'))}}
            <div class="mt-1"><span class="small">{{__('Note: Discount in Percentage')}}</span></div>
        </div>
        <div class="form-group col-md-6">
            {{Form::label('limit',__('Limit'),['class'=>'form-label'])}}<x-required></x-required>
            {{Form::number('limit',null,array('class'=>'form-control', 'placeholder' => 'Enter Limit','required'=>'required'))}}
        </div>
        <div class="form-group col-md-12">
            {{Form::label('code',__('Code'),['class'=>'form-label'])}}<x-required></x-required>
            {{Form::text('code',null,array('class'=>'form-control','required'=>'required'))}}
        </div>
    </div>
    <div class="modal-footer pb-0">
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
    </div>
</div>
{{ Form::close() }}
