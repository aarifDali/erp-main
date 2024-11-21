{{Form::open(array('url'=>'hotel-custom-page','method'=>'post','class'=>'needs-validation','novalidate'))}}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{Form::label('name',__('Name'),array('class'=>'form-label'))}}<x-required></x-required>
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Name'),'required'=>'required'))}}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('Page Header Display'), ['class' => 'form-label']) !!}
            <div class="form-check form-switch">
                <input type="hidden" name="enable_page_header" value="0">
                {!! Form::checkbox('enable_page_header', 1, 1, ['class' => 'form-check-input input-primary ', 'id' => 'customCheckdef1']) !!}
                <label class="form-check-label" for="customCheckdef1"></label>
            </div>
        </div>
        <div class="form-group col-md-12">
            {{Form::label('contents',__('Content'),array('class'=>'col-form-label')) }}
            {{Form::textarea('contents',null,array('class'=>'form-control summernote editor','id'=>'hotel-contents','rows'=>3,'placeholder'=>__('Content')))}}
        </div>
        <div class="form-group col-12 d-flex justify-content-end col-form-label">
            <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
            <input type="submit" value="{{__('Save')}}" class="btn btn-primary ms-2">
        </div>
    </div>
</div>
{{Form::close()}}


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
