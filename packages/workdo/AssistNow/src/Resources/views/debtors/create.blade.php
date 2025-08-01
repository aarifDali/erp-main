{{ Form::open(['route' => 'assistnow-debtors.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'repeater needs-validation', 'novalidate']) }}
<div class="modal-body">
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::label('', __('Debtor'), ['class' => 'form-label']) !!}<x-required></x-required>
        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Debtor Name','required'=> true]) !!}
    </div>
    <div class="modal-footer pb-0">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Save')}}" class="btn btn-primary bg-primary">
    </div>
</div>
