{{ Form::model($debtor, ['route' => ['assistnow-debtors.update', $debtor->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <input type="hidden" value{{ $debtor->id }}>
        
        <div class="form-group col-md-12">
            {!! Form::label('', __('Debtor'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::text('name', $debtor->name, [
                'class' => 'form-control',
                'placeholder' => 'Enter Debtor Name',
                'required' => true,
            ]) !!}
        </div>

        <div class="modal-footer pb-0">
            <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
            <input type="submit" value="{{__('Update')}}" class="btn btn-primary bg-primary">
        </div>
    </div>
</div>