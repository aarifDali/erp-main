{{ Form::model($service, ['route' => ['assistnow-services.update', $service->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <input type="hidden" value{{ $service->id }}>
        
        <div class="form-group col-md-12">
            {!! Form::label('', __('Service'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::text('name', $service->name, [
                'class' => 'form-control',
                'placeholder' => 'Enter Service Name',
                'required' => true,
            ]) !!}
        </div>

        <div class="form-group col-md-12">
            {!! Form::label('billing_interval', __('Billing Interval'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::text('billing_interval', $service->billing_interval, [
                'class' => 'form-control',
                'placeholder' => __('Enter Billing Interval (In Mins)'),
                'required' => true,
            ]) !!}
        </div>

        <div class="col-md-12">
            {!! Form::label('description', __('Description'), ['class' => 'form-label']) !!}
            {!! Form::textarea('description', $service->description, [
                'class' => 'form-control',
                'rows' => 3,
                'placeholder' => __('Add Description')
            ]) !!}
        </div>

        <div class="modal-footer pb-0">
            <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
            <input type="submit" value="{{__('Update')}}" class="btn btn-primary bg-primary">
        </div>
    </div>
</div>