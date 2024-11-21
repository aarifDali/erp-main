{{ Form::open(['route' => ['tour-hotelroom-booking.store'], 'method' => 'post', 'enctype' => 'multipart/form-data','id' => 'createform']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {!! Form::label('', __('Tour Inquiries'), ['class' => 'form-label']) !!}
            <select class="form-control" name="inquiry_id" id="inquiry_id" required>
                <option value="" data-rent-value="0">{{ __('Select Inquiry') }}</option>
                @foreach ($selectedTourInquiries as $selectedTourInquiry)
                    @foreach ($selectedTourInquiry as $InquiryDetail)
                        <option value="{{ $InquiryDetail['id'] }}">
                            {{ $InquiryDetail['tour_destination'] . '  --  ' . '[ ' . $InquiryDetail['person_name'] . ' ]' }}
                        </option>
                    @endforeach
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('email_id', __('Email Id'), ['class' => 'form-label']) !!}
            {!! Form::email('email_id',null, ['class' => 'form-control','placeholder' => 'Enter Email Id','required' => true,'id' => "inquiry_person_email_id",'readonly',]) !!}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('password', __('Password'), ['class' => 'form-label']) !!}
            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Enter Password', 'required' => true]) !!}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('person_name', __('Person Name'), ['class' => 'col-form-label']) }}
            <div id="value_id_name">
                <select class="form-control choices" name="person_name[]" id="multi_person_name" multiple>
                    <option value="">{{ __('Select Person Name') }}</option>
                </select>
            </div>
        </div>

        <div class="form-group col-md-12">
            {!! Form::label('', __('Room'), ['class' => 'form-label']) !!}
            <select class="form-control" name="room_id" id="room_id" required>
                <option value="" data-rent-value="0">{{ __('Select Room') }}</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}" data-rent-value="{{ $room->final_price }}">
                        {{ $room->room_type }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('Check In'), ['class' => 'form-label']) !!}
            {!! Form::date('check_in', null, [
                'class' => 'form-control check_in check_date',
                'data-url' => route('add.dayprice'),
                'required' => true,
                'min' => date('Y-m-d'),
            ]) !!}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('Check Out'), ['class' => 'form-label']) !!}
            {!! Form::date('check_out', null, [
                'class' => 'form-control check_out check_date',
                'data-url' => route('add.dayprice'),
                'required' => true,
                'min' => date('Y-m-d'),
            ]) !!}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('Total Room'), ['class' => 'form-label']) !!}
            {!! Form::number('room', '01', [
                'class' => 'form-control room',
                'data-url' => route('add.dayprice'),
                'placeholder' => 'Enter Total Room',
                'required' => true,
                'readonly',
            ]) !!}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('', __('Total Rent'), ['class' => 'form-label']) !!}
            {!! Form::number('total', null, ['class' => 'form-control total', 'required' => true, 'readonly' => true]) !!}
        </div>
        @php
            $paymentStatus = ['0' => 'UnPaid', '1' => 'Paid'];
        @endphp
        @php
            $payment = \Workdo\Holidayz\Entities\Utility::ActivePaymentGateway();
        @endphp

        <div class="form-group col-md-6">
            {!! Form::label('', __('Payment Method'), ['class' => 'form-label']) !!}
            {!! Form::select('payment_method', $payment, null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('', __('Payment Status'), ['class' => 'form-label']) !!}
            {!! Form::select('payment_status', $paymentStatus, null, ['class' => 'form-control']) !!}
        </div>
        <input type="hidden" name="tour_Id" value="{{ $tour_Id }}">
        <div class="modal-footer pb-0">
            <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
            <input type="submit" value="Create" class="btn btn-primary bg-primary">
        </div>
    </div>
</div>
{!! Form::close() !!}
<script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
<script>
    $(document).on('change', '#room_id', function(event) {
        var rent = $('option:selected', this).attr('data-rent-value');
        $('.sub_total').val(rent);
        $('.total').val(rent);
    });

    $(document).on('change', '.check_date', function() {
        const date1 = $('.check_in').val();
        const date2 = $('.check_out').val();

        if (date1 != "" && date2 != "") {
            var data = {
                room: $('option:selected', '#room_id').val(),
                total_room: $('.room').val(),
                date1: $('.check_in').val(),
                date2: $('.check_out').val()
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: $(this).attr('data-url'),
                data: data,
                cache: false,
                beforeSend: function() {
                    $('.booking-btn').attr('disabled');
                },
                success: function(data) {

                    $('.total').val(data.total_price);
                },
                complete: function() {
                    $('.booking-btn').removeAttr('disabled');
                },
            });
        }
    });

    $(document).on('change', '.room', function() {
        const date1 = $('.check_in').val();
        const date2 = $('.check_out').val();

        if (date1 != "" && date2 != "") {
            var data = {
                room: $('option:selected', '#room_id').val(),
                total_room: $('.room').val(),
                date1: $('.check_in').val(),
                date2: $('.check_out').val()
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "get",
                url: $(this).attr('data-url'),
                data: data,
                cache: false,
                beforeSend: function() {
                    $('.booking-btn').attr('disabled');
                },
                success: function(data) {

                    $('.total').val(data.total_price);
                },
                complete: function() {
                    $('.booking-btn').removeAttr('disabled');
                },
            });
        }
    });


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
    $(document).on("change", "#inquiry_id", function() {
        var Inquiry_Id = $(this).val();
        var tour_Id = {{ $tour_Id }};
        $.ajax({
            url: '{{ route('get.person.name.in.create') }}',
            type: 'POST',
            data: {
                "Inquiry_Id": Inquiry_Id,
                "tour_Id": tour_Id,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                var allPersonName = data.allPersonName
                $('#value_id_name').empty();
                var option =
                    '<select class="form-control choices" name="value[]" id="values_name" placeholder="{{ __('Select Person Name') }}"  multiple>';
                option += '<option value="" disabled>{{ __('Select Person Name') }}</option>';

                $.each(allPersonName, function(key, value) {
                    option += '<option value="' + key + '">' + value + '</option>';
                });
                option += '</select>';

                $("#value_id_name").append(option);
                var multipleCancelButton = new Choices('#values_name', {
                    removeItemButton: true,
                });

                // Update the email field with the retrieved email ID
                $('#inquiry_person_email_id').val(data.inquiryPersonEmailId);
            },
        });
    });
</script>
<script>
    document.getElementById('createform').addEventListener('submit', function(event) {
        var selectedNames = document.getElementById('values_name').value;
        if (!selectedNames || selectedNames.length === 0) {
            event.preventDefault(); // Prevent form submission
            toastrs('{{ __('Error') }}', '{{ __('PleaseSelect Person Name') }}', 'error');
        }
    });
</script>
