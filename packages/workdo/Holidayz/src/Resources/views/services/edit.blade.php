@php
    $path = get_file('uploads');
@endphp
<link rel="stylesheet" href="{{ asset('packages/workdo/Holidayz/src/Resources/assets/bootstrap-iconpicker/css/bootstrap-iconpicker.css') }}" />
{{ Form::model($service, ['route' => ['hotel-services.update', $service->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'class' => 'repeater needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <input type="hidden" value{{ $service->id }}>
        <div class="form-group col-md-12">
            {!! Form::label('', __('Parent Service'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::text('name', $service->name, [
                'class' => 'form-control',
                'placeholder' => 'Enter Service Name',
                'required' => true,
            ]) !!}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}<x-required></x-required>
            <div class="input-group mb-3">
                {!! Form::file('file', ['class' => 'form-control', 'id' => 'inputGroupFile01', 'id' => 'is_cover_image']) !!}
            </div>
            <img src="{{ $path . '/amenities/' . $service->image }}" class="w-100 h-50" />
        </div>
        <div class="col-6">
            <span data-icon="{{ $service->icon }}" data-cols="6" data-align="center" data-search="true"
                data-search-text="{{ __('Search...') }}" data-iconset="fontawesome5" role="iconpicker"
                name="icon"></span>
        </div>
        <div class="form-group d-flex" style="justify-content: space-between">
            {!! Form::label('', __('Child Service'), ['class' => 'form-label']) !!}
            <button data-repeater-create type="button" class="btn btn-outline-secondary" value="Add"><i
                    class="ti ti-plus"></i></button>
        </div>
        <div data-repeater-list="category-group">
            @if (count($service->getSubServices) > 0)
                @foreach ($service->getSubServices as $item)
                    <div class="form-group input-group col-md-12 pt-2" data-repeater-item>
                        <input type="hidden" name="id" id="cat-id" />
                        {!! Form::text('sub_services', $item->name, [
                            'class' => 'form-control',
                            'placeholder' => 'Enter Child Service Name',
                            'required' => true,
                        ]) !!}
                        <button class="btn btn-outline-secondary" data-repeater-delete type="button" id="add"><i
                                class="ti ti-trash"></i></button>
                    </div>
                @endforeach
            @else
                <div class="form-group input-group col-md-12 pt-2" data-repeater-item>
                    <input type="hidden" name="id" id="cat-id" />
                    {!! Form::text('sub_services', null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Child Service Name',
                        'required' => true,
                    ]) !!}
                    <button class="btn btn-outline-secondary" data-repeater-delete type="button" id="add"><i
                            class="ti ti-trash"></i></button>
                </div>
            @endif
        </div>
        <div class="modal-footer pb-0">
            <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
            <input type="submit" value="Update" class="btn btn-primary bg-primary">
        </div>
    </div>
</div>
@include('holidayz::layouts.repeater_script')
{!! Form::close() !!}
<script type="text/javascript"
    src="{{ asset('packages/workdo/Holidayz/src/Resources/assets/bootstrap-iconpicker/js/bootstrap-iconpicker.bundle.min.js') }}"></script>
<script>
    if ($('[role="iconpicker"]').length > 0) {
        $('[role="iconpicker"]').iconpicker();
    }
</script>
