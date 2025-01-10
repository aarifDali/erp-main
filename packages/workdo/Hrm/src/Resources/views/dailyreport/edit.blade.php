@extends('layouts.main')
@section('page-title')
    {{ __('Daily  Edit') }}
@endsection
@section('page-breadcrumb')
    {{ __('Daily Report') }},
    {{ __('Edit') }}
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

                    const fileInput = $(this).find('input.file-input');
                    const filePreview = $(this).find('img.file-preview');

                    fileInput.val(''); // Clear file input
                    filePreview.attr('src', '').hide(); // Reset image preview

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
{{ Form::open(['url' => route('daily-report.update', $dailyReport->id), 'method' => 'put', 'class' => 'needs-validation', 'novalidate', 'enctype' => 'multipart/form-data']) }}
<div class="col-12">
    <div class="card repeater">
        <div class="item-section py-2">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-center">
                    <div class="all-button-box me-2">
                        <div class="form-group">
                            {{ Form::label('report_date', __('Date'), ['class' => 'col-form-label']) }}<x-required></x-required>
                            {{ Form::date('report_date', $dailyReport->report_date, ['class' => 'form-control btn btn-info', 'readonly' => 'readonly']) }}
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
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Attachment') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="ui-sortable">
                        @foreach($dailyReport->tasks as $task)
                        <tr data-repeater-item>
                            <td class="sr-no text-center">{{ $loop->iteration }}</td>
                            <td width="25%" class="form-group pt-0">
                                {{ Form::textarea('description', $task->description, ['class' => 'form-control', 'style' => 'resize: none;', 'rows' => 1, 'required' => 'required', 'placeholder' => __('Enter Task Description')]) }}
                            </td>
                            <td>
                                {{ Form::time('start_time',  $task->start_time, ['class' => 'form-control', 'placeholder' => __('Start Time')]) }}
                            </td>
                            <td>
                                {{ Form::time('end_time',  $task->end_time, ['class' => 'form-control', 'placeholder' => __('End Time')]) }}
                            </td>
                            <td width="25%" class="form-group pt-0">
                                <select name="status" class="form-control status_id js-searchBox" required>
                                    <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="Pending" {{ $task->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                            </td>
                            <td>
                                <div class="file-upload-wrapper">
                                    <label class="btn btn-secondary">
                                        {{ __('Choose File') }}
                                        <input type="file" name="attachment" class="file-input" 
                                            onchange="previewFile(this, this.closest('tr').querySelector('.file-preview'))" hidden>
                                    </label>
                                    @if(!empty($task->attachment))
                                        <img src="{{ asset('storage/app/public/attachments/' . $task->attachment) }}" class="file-preview mt-2" width="80" height="80" />
                                        <input type="hidden" name="existing_attachment" value="{{ $task->attachment }}">
                                    @else
                                        <img src="" class="file-preview mt-2" width="80" height="80" style="display:none;" />
                                    @endif
                                </div> 
                            </td>
                            
                            <td>
                                <div class="action-btn ms-2 float-end mb-3" data-repeater-delete>
                                    <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center m-2 p-2 bg-danger">
                                        <i class="ti ti-minus text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Delete') }}"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="item-section py-2">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                            <div class="all-button-box me-2">
                                <a href="#" data-repeater-create="" class="btn btn-primary">
                                    <i class="ti ti-plus"></i> {{ __('Add task') }}
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 offset-md-2">
                                <div class="form-group">
                                    {{ Form::label('remarks', __('Remarks (Optional)'), ['class' => 'form-label']) }}
                                    {{ Form::textarea('remarks', $dailyReport->remarks, ['class' => 'form-control', 'style' => 'resize: none;', 'rows' => 2, 'placeholder' => __('Enter Remarks')]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" onclick="location.href = '{{ route('daily-report.index') }}';" class="btn btn-light">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary" id="submit">
</div>
{{ Form::close() }}
@endsection
