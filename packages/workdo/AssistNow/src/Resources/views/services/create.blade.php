{{ Form::open(['route' => 'assistnow-services.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'repeater needs-validation', 'novalidate']) }}
<div class="modal-body">
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::label('', __('Service'), ['class' => 'form-label']) !!}<x-required></x-required>
        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Service Name','required'=> true]) !!}
    </div>
    <div class="form-group col-md-12">
        {!! Form::label('', __('Billing Interval'), ['class' => 'form-label']) !!}<x-required></x-required>
        {!! Form::text('billing_interval', null, ['class' => 'form-control', 'placeholder' => 'Enter Billing Interval (In Mins)','required'=> true]) !!}
    </div>
    <div class="col-md-12">
        {{ Form::label('', __('Description'),['class'=>'form-label']) }}
        {{ Form::textarea('description', '', array('class' => 'form-control','rows'=>3,'id'=>"description",'placeholder'=> __('Add Description'))) }}
    </div>
    <div class="modal-footer pb-0">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn btn-primary bg-primary">
    </div>
</div>
