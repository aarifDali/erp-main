<link rel="stylesheet" href="{{ asset('packages/workdo/Holidayz/src/Resources/assets/bootstrap-iconpicker/css/bootstrap-iconpicker.css') }}" />
{{ Form::model($feature, ['route' => ['hotel-room-features.update', $feature->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data','class'=>'needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {!! Form::label('', __('Name'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::text('name', null, [
                'class' => 'form-control',
                'placeholder' => 'Enter Feature Name',
                'required' => true,
            ]) !!}
        </div>
        <div class="col-12 pt-4">
            <span data-icon="{{ $feature->icon }}" data-cols="6" data-align="center" data-search="true"
                data-search-text="{{ __('Search...') }}" data-iconset="fontawesome5" role="iconpicker"
                name="icon"></span>
            <input type="hidden" name="old_icon" value="{{ $feature->icon }}">
        </div>
        <div class="modal-footer pb-0">
            <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
            <input type="submit" value="Update" class="btn btn-primary bg-primary">
        </div>
    </div>
</div>
{!! Form::close() !!}
<script type="text/javascript" src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/bootstrap-iconpicker/js/bootstrap-iconpicker.bundle.min.js') }}">
</script>
<script>
    if ($('[role="iconpicker"]').length > 0) {
        $('[role="iconpicker"]').iconpicker();
    }
</script>
