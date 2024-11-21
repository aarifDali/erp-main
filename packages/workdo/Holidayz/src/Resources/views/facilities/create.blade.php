{{ Form::open(['route' => 'hotel-room-facilities.store', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'repeater needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {!! Form::label('', __('Name'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::text('name', null, [
                'class' => 'form-control',
                'required' => true,
                'placeholder' => 'Please Enter Name',
            ]) !!}
        </div>
        <div class="form-group col-md-12">
            {!! Form::label('', __('Short Description'), ['class' => 'form-label']) !!}
            {!! Form::textarea('short_description', null, [
                'class' => 'form-control summernote',
                'rows' => 5,
                'cols' => 5,
                'id' => 'hotel-short-desc',
                'placeholder' => 'Please Enter Short Description',
            ]) !!}
        </div>

        <div class="form-group col-12 switch-width">
            {{ Form::label('tax_id', __('Tax'), ['class' => 'form-label']) }}
            {{ Form::select('tax_id[]', $taxes, null, ['class' => 'form-control choices', 'id' => 'choices-multiple1', 'multiple']) }}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('Enable'), ['class' => 'form-label']) !!}
            <div class="form-check form-switch">
                <input type="hidden" name="status" value="0">
                {!! Form::checkbox('status', 1, 1, ['class' => 'form-check-input input-primary', 'id' => 'customCheckdef1']) !!}
                <label class="form-check-label" for="customCheckdef1"></label>
            </div>
        </div>
        <div class="col-md-12 d-flex form-group  justify-content-between">
            {!! Form::label('', __('Child Facilities'), ['class' => 'form-label']) !!}
            <button data-repeater-create type="button" class="btn btn-outline-secondary" value="Add"> <i
                    class="ti ti-plus"></i></button>
        </div>
        <div data-repeater-list="child_facilities">
            <div class="input-group col-md-12 pt-2" data-repeater-item>
                <input type="hidden" name="id" id="cat-id" />
                {!! Form::text('sub_name', null, [
                    'class' => 'form-control mx-1',
                    'placeholder' => 'Enter Name',
                    'required' => true,
                ]) !!}
                {!! Form::number('sub_price', null, [
                    'class' => 'form-control',
                    'placeholder' => 'Enter Price',
                    'required' => true,
                ]) !!}
                <button class="btn btn-outline-secondary" data-repeater-delete type="button" id="add"><i
                        class="ti ti-trash"></i></button>
            </div>
        </div>
        <div class="modal-footer pb-0">
            <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
            <input type="submit" value="Create" class="btn btn-primary bg-primary">
        </div>
    </div>
</div>

{!! Form::close() !!}

<script>
    $(document).ready(function() {
        select2();
    });

    function select2() {
        if ($(".select2").length > 0) {
            $($(".select2")).each(function(index, element) {
                var id = $(element).attr('id');
                var multipleCancelButton = new Choices(
                    '#' + id, {
                        removeItemButton: true,
                    }
                );
            });
        }
    }
</script>
<script>

if ($(".summernote").length > 0) {
    $('.summernote').summernote({
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough']],
            ['list', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'unlink']],
        ],
        height: 200,
    });
}

</script>
@include('holidayz::layouts.repeater_script')
