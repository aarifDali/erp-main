<style>
    .dropzone .avatar img{
        width: 100%;
    }
    .avatar {
    position: relative;
    color: #FFF;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    vertical-align: middle;
    font-size: 1rem;
    font-weight: 600;
    height: 3.125rem;
    width: 3.125rem;
}
[dir="rtl"] .end-0 {
    left: 0 !important;
    right: 0 !important;
}
</style>
{{ Form::model($room, ['route' => ['rooms.update', $room->id], 'id' => 'room', 'method' => 'PUT', 'enctype' => 'multipart/form-data','class'=>'submit-room needs-validation','novalidate']) }}

@php
    $logo = get_file('uploads/rooms');
@endphp
<div class="modal-body">
    <div class="row">
        
        <div class="form-group col-md-12">
            {!! Form::label('apartment_type_id', 'Apartment Type:', ['class' => 'form-label']) !!}
            {!! Form::select('apartment_type_id', $apartmentTypes->pluck('name', 'id'), old('apartment_type_id', $room->apartment_type_id), [
                'class' => 'form-control',
                'placeholder' => 'Select Apartment Type',
                'required' => true,
            ]) !!}
        </div>

        <div class="form-group col-md-12">
            {!! Form::label('', __('Room Type'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::text('room_type', null, [
                'class' => 'form-control',
                'placeholder' => 'Enter Room Type',
                'required' => true,
            ]) !!}
        </div>
        <div class="form-group col-md-12">
            {!! Form::label('', __('Short Description'), ['class' => 'form-label']) !!}
            {!! Form::textarea('short_description', null, [
                'class' => 'form-control summernote',
                'placeholder' => 'Enter Description',
                'rows' => '2',
                'id' => 'hotel-desc-short',
            ]) !!}
        </div>
        <div class="form-group">
            <input type="hidden" name="tags[]" value="">
            {!! Form::label('', __('Tags'), ['class' => 'form-label']) !!}
            {!! Form::text('tags', null, [
                'class' => 'form-control',
                'data-role' => 'tagsinput',
                'id' => 'tags',
            ]) !!}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('Adults'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::number('adults', null, [
                'class' => 'form-control',
                'placeholder' => 'Enter Adults',
                'required' => true,
            ]) !!}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('Children'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::number('children', null, [
                'class' => 'form-control',
                'placeholder' => 'Enter Children',
                'required' => true,
            ]) !!}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('Total Room'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::number('total_room', null, [
                'class' => 'form-control',
                'placeholder' => 'Enter Total Room',
                'required' => true,
            ]) !!}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('Base Price'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::number('base_price', null, [
                'class' => 'form-control',
                'placeholder' => 'Enter Base Price',
                'required' => true,
            ]) !!}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('Final Price'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::number('final_price', null, [
                'class' => 'form-control',
                'placeholder' => 'Enter Final Price',
                'required' => true,
            ]) !!}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}<x-required></x-required>
            <div class="input-group mb-3">
                {!! Form::file('file', ['class' => 'form-control', 'id' => 'is_cover_image']) !!}
            </div>
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('', __('Enable'), ['class' => 'form-label']) !!}
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" id="status" name="status"
                    {{ $room->is_active == 1 ? 'checked' : '' }} />
                <label class="form-check-label f-w-600 pl-1" for="status"></label>
            </div>
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('Image Preview'), ['class' => 'form-label']) !!}<x-required></x-required>
            <div class="form-check form-switch">
                <img id="image"
                    src="{{ $logo . '/' . (isset($room->image) && !empty($room->image) ? $room->image : 'logo-dark.png') }}"
                    class="wid-150 rounded">
            </div>
        </div>
        <div class="form-group col-md-12">
            {!! Form::label('', __('Description'), ['class' => 'form-label']) !!}
            {!! Form::textarea('description', null, [
                'class' => 'form-control summernote',
                'placeholder' => 'Enter Description',
                'rows' => '4',
                'cols' => '50',
                'id' => 'hotel-desc',
            ]) !!}
        </div>
        <div class="col-md-12">
            <div class="dropzone dropzone-multiple" data-toggle="dropzone1" data-dropzone-url="http://"
                data-dropzone-multiple>
                <div class="fallback">
                    <div class="custom-file">
                        <input type="file" name="file" id="dropzone-1" class="fcustom-file-input"
                            onchange="document.getElementById('dropzone').src = window.URL.createObjectURL(this.files[0])"
                            multiple>
                        <img id="dropzone"src="" width="20%" class="mt-2" />
                        <label class="custom-file-label" for="customFileUpload">{{ __('Choose file') }}</label>
                    </div>
                </div>
                <ul class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush">
                    <li class="list-group-item px-0">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar">
                                    <img class="rounded" src="" alt="Image placeholder" data-dz-thumbnail>
                                </div>
                            </div>
                            <div class="col">
                                <h6 class="text-sm mb-1" data-dz-name>...</h6>
                                <p class="small text-muted mb-0" data-dz-size>
                                </p>
                            </div>
                            <div class="col-auto">
                                <a href="#" class="dropdown-item" data-dz-remove>
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        @php
        @endphp
        <div class="form-group ">
            <div class="row gy-3 gx-3 mt-3">
                @foreach ($room->getImages as $file)
                    <div class="col-sm-3 product_Image remove_{{ $file->id }}" data-id="{{ $file->id }}">
                        <div class="position-relative p-2 border rounded border-primary overflow-hidden rounded">
                            <img src="{{ $file->name }}" alt="" class="w-100">
                            <div class="position-absolute text-center top-50 end-0 start-0 ps-3 pb-3">
                                <a href="{{ $file->name }}" download="" data-original-title="{{ __('Download') }}"
                                    class="btn btn-sm btn-primary me-2"><i class="ti ti-download"></i></a>
                                <a class="btn btn-sm btn-danger text-white deleteRecord" name="deleteRecord"
                                    data-id="{{ $file->id }}"><i class="ti ti-trash"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="modal-footer pb-0">
            <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
            <input type="submit" value="Update" class="btn btn-primary bg-primary" id="submit-all">
        </div>
    </div>
</div>
<input type="hidden" name="room_id" class="room_id" value="{{ $room->id }}">
{!! Form::close() !!}
<link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.min.css') }}">
<script src="{{ asset('assets/js/plugins/dropzone-amd-module.min.js') }}"></script>
<script>
    var Dropzones = function() {
        var roomId = $('.room_id').val();
        var e = $('[data-toggle="dropzone1"]'),
            t = $(".dz-preview");
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        e.length && (Dropzone.autoDiscover = !1, e.each(function() {
            var e, a, n, o, i;
            e = $(this), a = void 0 !== e.data("dropzone-multiple"), n = e.find(t), o = void 0, i = {
                url: "rooms/" + roomId,
                headers: {
                    'x-csrf-token': CSRF_TOKEN,
                },
                thumbnailWidth: null,
                thumbnailHeight: null,
                previewsContainer: n.get(0),
                previewTemplate: n.html(),
                maxFiles: 10,
                parallelUploads: 10,
                autoProcessQueue: false,
                uploadMultiple: true,
                acceptedFiles: a ? null : "image/*",
                success: function(file, response) {
                    if (response.flag == "success") {
                        toastrs('success', response.msg, 'success');
                        window.location.href = "{{ route('hotel-rooms.index') }}";
                    } else {
                        toastrs('Error', response.msg, 'error');
                    }
                },
                error: function(file, response) {
                    // Dropzones.removeFile(file);
                    if (response.error) {
                        toastrs('Error', response.error, 'error');
                    } else {
                        toastrs('Error', response, 'error');
                    }
                },
                init: function() {
                    var myDropzone = this;
                    this.on("addedfile", function(e) {
                        !a && o && this.removeFile(o), o = e
                    })
                }
            }, n.html(""), e.dropzone(i)
        }))
    }()

    // $('#submit-all').on('click', function(event) {
    $(document).on("submit", ".submit-room", function (event) {
        event.preventDefault();
        
        var roomId = $('.room_id').val();
        $('#submit-all').attr('disabled', true);
        var fd = new FormData();
        var file = document.getElementById('is_cover_image').files[0];
        if (file) {
            fd.append('file', file);
        }
        var files = $('[data-toggle="dropzone1"]').get(0).dropzone.getAcceptedFiles();
        $.each(files, function(key, file) {
            fd.append('multiple_files[' + key + ']', $('[data-toggle="dropzone1"]')[0].dropzone
                .getAcceptedFiles()[key]); // attach dropzone image element
        });
        var other_data = $('#room').serializeArray();
        $.each(other_data, function(key, input) {
            fd.append(input.name, input.value);
        });
        $.ajax({
            url: "rooms/" + roomId,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: fd,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function(data) {
                if (data.flag == "success") {
                    $('#submit-all').attr('disabled', true);
                    toastrs('success', data.msg, 'success');
                    setTimeout(function() {
                        window.location.href = "{{ route('hotel-rooms.index') }}";
                    }, 100);
                } else {
                    toastrs('Error', data.msg, 'error');
                    $('#submit-all').attr('disabled', false);
                }
            },
            error: function(data) {
                $('#submit-all').attr('disabled', false);
                // Dropzones.removeFile(file);
                if (data.error) {
                    toastrs('Error', data.error, 'error');
                } else {
                    toastrs('Error', data, 'error');
                }
            },
        });
    });

    $(".deleteRecord").click(function() {
        var id = $(this).data("id");
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            url: '{{ route('images.delete', 'id') }}'.replace('id', id),
            type: 'DELETE',
            data: {
                "id": id,
                "_token": token,
            },
            success: function(data) {
                if (data.success) {
                    toastrs('success', data.message, 'success');
                    $('.product_Image[data-id="' + data.id + '"]').remove();
                } else {
                    toastrs('Error', data.error, 'error');
                }
            }
        });
    });
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
