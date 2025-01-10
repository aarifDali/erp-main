@extends('layouts.main')
@section('page-title')
    {{ __('Daily Report Create') }}
@endsection
@section('page-breadcrumb')
    {{ __('Daily Report') }},
    {{ __('Create') }}
@endsection
@push('scripts')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('js/jquery-searchbox.js') }}"></script>
    <script>
        var selector = "body";
        if ($(selector + " .repeater").length) {
            var $dragAndDrop = $("body .repeater tbody").sortable({
                handle: '.sort-handler',
                stop: function() {
                    updateSerialNumbers(); 
                }
            });
    
            var $repeater = $(selector + ' .repeater').repeater({
                initEmpty: false,
                defaultValues: {
                    'status': 1
                },
                show: function() {
                    $(this).slideDown();
                    
                    // Handle file uploads (if any)
                    var file_uploads = $(this).find('input.multi');
                    if (file_uploads.length) {
                        $(this).find('input.multi').MultiFile({
                            max: 3,
                            accept: 'png|jpg|jpeg',
                            max_size: 2048
                        });
                    }
    
                    JsSearchBox();
                    updateSerialNumbers();
                },
                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement, function() {
                        $(this).remove();
                        updateSerialNumbers();
                    });
                },
                ready: function(setIndexes) {
                    $dragAndDrop.on('drop', setIndexes);
                    updateSerialNumbers();
                },
                isFirstItemUndeletable: true
            });
    
            var value = $(selector + " .repeater").attr('data-value');
            if (typeof value != 'undefined' && value.length != 0) {
                value = JSON.parse(value);
                $repeater.setList(value);
            }
    
            
            function updateSerialNumbers() {
                $(selector + ' .repeater tbody tr').each(function(index) {
                    $(this).find('.sr-no').text(index + 1);
                });
            }
        }
    </script>
    
    <script>
        JsSearchBox();
        $(document).on('change', '.product_type', function() {
            var product_type = $(this).val();
            var selector = $(this);
            var itemSelect = selector.parent().parent().find('.product_id.item').attr('name');
            console.log(itemSelect);
            $.ajax({
                url: '{{ route('get.item') }}',
                type: 'POST',
                data: {
                    "product_type": product_type,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    selector.parent().parent().find('.product_id').empty();
                    var product_select = `<select class="form-control product_id item js-searchBox" name="${itemSelect}"
                                            placeholder="Select Item" data-url="{{ route('purchases.product') }}" required = 'required'>
                                            </select>`;
                    selector.parent().parent().find('.product_div').html(product_select);

                    selector.parent().parent().find('.product_id').append(
                        '<option value="0"> {{ __('Select Item') }} </option>');
                    $.each(data, function(key, value) {
                        selector.parent().parent().find('.product_id').append(
                            '<option value="' + key + '">' + value +
                            '</option>');
                    });

                    Items(selector.parent().parent().find('.product_id'));
                    selector.parent().parent().find(".js-searchBox").searchBox({
                        elementWidth: '250'
                    });
                    selector.parent().parent().find('.unit.input-group-text').text("");
                    selector.parent().parent().find('.taxes .product_tax').text("");
                }
            });
        });
    </script>
    <script>
        function previewFile(input, previewElement) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                if (file.type.startsWith('image/')) {
                    previewElement.src = URL.createObjectURL(file);
                    previewElement.style.display = 'block';
                } else {
                    previewElement.style.display = 'none';
                    alert('Please select a valid image file (png, jpg, jpeg).');
                }
            }
        }
    </script>
@endpush

@section('content')
{{ Form::open(['url' => 'daily-report', 'method' => 'post', 'class' => 'needs-validation', 'novalidate', 'enctype' => 'multipart/form-data']) }}
<div class="col-12">
    <div class="card repeater">
        <div class="item-section py-2">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-center">
                    <div class="all-button-box me-2">
                        <div class="form-group">
                            {{ Form::label('report_date', __('Date'), ['class' => 'col-form-label']) }}<x-required></x-required>
                            {{ Form::date('report_date', date('Y-m-d'), ['class' => 'form-control btn btn-info', 'readonly' => 'readonly']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <table class="table mb-0" data-repeater-list="tasks" id="sortable-table">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.No') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Start Time') }}</th>
                            <th>{{ __('End Time') }}</th>
                            <th>{{ __('Status') }} </th>
                            <th>{{ __('Attachment') }}</th>
                            
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="ui-sortable" data-repeater-item>
                        <tr>
                            <td class="sr-no text-center">1</td>
                            <td width="25%" class="form-group pt-0">
                                {{ Form::textarea('description', null, ['class' => 'form-control', 'style' => 'resize: none;', 'rows' => 1, 'required' => 'required', 'placeholder' => __('Enter Task Description')]) }}
                            </td>
                            <td>
                                {{ Form::time('start_time', null, ['class' => 'form-control', 'placeholder' => __('Start Time')]) }}
                            </td>
                            <td>
                                {{ Form::time('end_time', null, ['class' => 'form-control', 'placeholder' => __('End Time')]) }}
                            </td>
                            <td width="25%" class="form-group pt-0">
                                <select name="status" class="form-control status_id js-searchBox" required>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Pending">Pending</option> 
                                </select>
                            </td>
                            <td>
                                <div class="file-upload-wrapper">
                                    <label class="btn btn-secondary">
                                        {{ __('Choose File') }}
                                        <input type="file" name="attachment" class="file-input" 
                                            onchange="previewFile(this, this.closest('tr').querySelector('.file-preview'))" hidden>
                                    </label>
                                    <img src="" class="file-preview mt-2" width="80" height="80" style="display:none;" />
                                </div>
                            </td>
                            
                            <td>
                                <div class="action-btn ms-2 float-end mb-3" data-repeater-delete>
                                    <a href="#!"
                                        class="mx-3 btn btn-sm d-inline-flex align-items-center m-2 p-2 bg-danger">
                                          <i class="ti ti-minus text-white" data-bs-toggle="tooltip"
                                          data-bs-original-title="{{ __('Delete') }}" ></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="item-section py-2">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                            <div class="all-button-box me-2">
                                <a href="#" data-repeater-create="" class="btn btn-primary" data-bs-toggle="modal"
                                    data-target="#add-bank">
                                    <i class="ti ti-plus"></i> {{ __('Add task') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 offset-md-2">
                            <div class="form-group">
                                {{ Form::label('remarks', __('Remarks (Optional)'), ['class' => 'form-label']) }}
                                {{ Form::textarea('remarks', null, ['class' => 'form-control', 'style' => 'resize: none;', 'rows' => 2, 'placeholder' => __('Enter Remarks')]) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}"
        onclick="location.href = '{{ route('daily-report.index') }}';" class="btn btn-light">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary" id="submit">
</div>
{{ Form::close() }}
@endsection