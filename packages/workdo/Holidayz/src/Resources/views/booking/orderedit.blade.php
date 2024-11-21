{{ Form::model($bookingorders, ['route' => ['bookingorder.update', $bookingorders->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data','class'=>'needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        
        <div class="form-group col-md-12">
            {!! Form::label('apartment_type_id', 'Apartment Type:', ['class' => 'form-label']) !!}
            {!! Form::select('apartment_type_id', $apartmentTypes->pluck('name', 'id'), old('apartment_type_id', $bookingorders->apartment_type_id), [
                'class' => 'form-control',
                'placeholder' => 'Select Apartment Type',
                'required' => true,
            ]) !!}
        </div>
        
        <div class="form-group col-md-12">
            {!! Form::label('', __('Room'), ['class' => 'form-label']) !!}<x-required></x-required>
            <select class="form-control" name="room_id" id="room_id" required>
                <option value="" data-rent-value="0">{{ __('Select Room') }}</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}" data-rent-value="{{ $room->final_price }}" 
                        @if($room->id == $bookingorders->room_id) selected @endif>
                        {{ $room->room_type }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('', __('Check In'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::date('check_in', null, ['class' => 'form-control check_in check_date', 'data-url'=>route('add.dayprice')]) !!}
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('', __('Check Out'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::date('check_out', null, ['class' => 'form-control check_out check_date', 'data-url'=>route('add.dayprice')]) !!}
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('', __('Adults'), ['class' => 'form-label']) !!}
            {!! Form::number('adults', $bookingorders->getRoomDetails->adults, [
                'class' => 'form-control',
                'placeholder' => 'Enter Adults',
                'required' => true,
                'readonly' => true,
            ]) !!}
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('', __('Total Room'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::number('room', null, [
                'class' => 'form-control room', 'data-url'=>route('add.dayprice'),
                'placeholder' => 'Enter Total Room',
                'required' => true,
            ]) !!}
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('', __('Children'), ['class' => 'form-label']) !!}
            {!! Form::number('children', $bookingorders->getRoomDetails->children, [
                'class' => 'form-control',
                'placeholder' => 'Enter Children',
                'required' => true,
                'readonly' => true,
            ]) !!}
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('', __('Room Rent'), ['class' => 'form-label']) !!}
            {!! Form::number('sub_total', $bookingorders->price, [
                'class' => 'form-control sub_total',
                'required' => true,
                'readonly' => true,
            ]) !!}
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('', __('Service Charge'), ['class' => 'form-label']) !!}
            {!! Form::number('service_charge', $bookingorders->service_charge, [
                'class' => 'form-control service_charge',
                'required' => true,
                // 'disabled' => true,
                'readonly' => true,
            ]) !!}
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('discount_amount', __('Discount Amount'), ['class' => 'form-label']) !!}
            <div class="input-group">
                {!! Form::number('discount_amount', $bookingorders->discount_amount, ['class' => 'form-control', 'id' => 'discount_amount', 'step' => '0.01']) !!}
                <button type="button" class="btn btn-primary" id="applyDiscountButton">Apply</button>
            </div>
            <span id="discountErrorMessage" class="text-danger" style="display:none;"></span>
        </div>
        
        <div class="form-group col-md-6">
            {!! Form::label('', __('Total Rent'), ['class' => 'form-label']) !!}
            {!! Form::number('amount_to_pay', $bookingorders->amount_to_pay, ['class' => 'form-control amount-to-pay', 'required' => true, 'readonly' => true]) !!}
        </div>
        

        <div class="modal-footer pb-0">
            <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
            <input type="submit" value="Update" class="btn btn-primary bg-primary">
        </div>
    </div>
</div>
{!! Form::close() !!}

{{-- <script>
    $(document).on('change', '#room_id', function(event) {
        var rent = $('option:selected', this).attr('data-rent-value');
        var service_charge = $('.service_charge').val();
        rent = parseInt(rent);
        service_charge = parseInt(service_charge);
        $('.sub_total').val(rent);
        $('.total').val(rent + service_charge);
    });

    $(document).on('change', '.check_date',function(){
        const date1 = $('.check_in').val();
        const date2 = $('.check_out').val();

        if(date1 != "" && date2 != "" ){
            var data = {
                    room: $('option:selected', '#room_id').val(),
                    total_room:$('.room').val(),
                    service_charge:"{{$bookingorders->service_charge}}",
                    date1:$('.check_in').val(),
                    date2:$('.check_out').val()
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
                        $('.total').val(data.total_price + data.service_price);
                        $('.sub_total').val(data.total_price);
                        $('.service_charge').val(data.service_price);
                    },
                    complete: function() {
                        $('.booking-btn').removeAttr('disabled');
                    },
                });
        }
    });

    $(document).on('change', '.room',function(){
        const date1 = $('.check_in').val();
        const date2 = $('.check_out').val();

        if(date1 != "" && date2 != "" ){
            var data = {
                    room: $('option:selected', '#room_id').val(),
                    total_room:$('.room').val(),
                    service_charge:"{{$bookingorders->service_charge}}",
                    date1:$('.check_in').val(),
                    date2:$('.check_out').val()
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
                        $('.total').val(data.total_price + data.service_price);
                        $('.sub_total').val(data.total_price);
                        $('.service_charge').val(data.service_price);
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
</script> --}}

{{-- <script>
    $(document).ready(function() {
        $('#apartment_type_id').change(function() {
            const selectedApartmentTypeId = $(this).val();
            $('#room_id').empty().append('<option value="" data-rent-value="0">{{ __('Select Room') }}</option>'); // Clear existing rooms

            if (selectedApartmentTypeId) {
                $.ajax({
                    url: '{{ route("fetch.rooms") }}',
                    type: 'GET',
                    data: { apartment_type_id: selectedApartmentTypeId },
                    success: function(response) {
                        if (response.rooms && response.rooms.length > 0) {
                            response.rooms.forEach(function(room) {
                                $('#room_id').append(`
                                    <option value="${room.id}" data-apartment-type-id="${room.apartment_type_id}" data-rent-value="${room.final_price}">
                                        ${room.room_type}
                                    </option>
                                `);
                            });
                        }
                    },
                    error: function() {
                        console.log('Error fetching rooms.');
                    }
                });
            }
        });

        $('#room_id').change(function () {
            const selectedOption = this.options[this.selectedIndex];
            const totalRent = parseFloat(selectedOption.getAttribute('data-rent-value'));

            $('.total').val(totalRent.toFixed(2));
            updateAmountToPay(totalRent);  
        });

        $('#discountCheckbox').change(function () {
            const totalRent = parseFloat($('.total').val()) || 0;
            updateAmountToPay(totalRent);  
        });

        function updateAmountToPay(totalRent) {
            let amountToPay = totalRent;
            let discountMessage = $('#discountMessage');

            if ($('#discountCheckbox').is(':checked')) {
                const discount = totalRent * 0.05;
                amountToPay = totalRent - discount; 
                discountMessage.text(`$${discount.toFixed(2)} saved`).show();  
            } else {
                discountMessage.hide();  
            }

            $('.amount-to-pay').val(amountToPay.toFixed(2)); 
        }

        $('.check_date, .room').change(function() {
            const date1 = $('.check_in').val();
            const date2 = $('.check_out').val();
            const totalRooms = $('.room').val();
            const roomId = $('#room_id').val();

            if (date1 && date2 && totalRooms && roomId) {
                const data = {
                    room: roomId,
                    total_room: totalRooms,
                    date1: date1,
                    date2: date2
                };

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "get",
                    url: $('.check_date').attr('data-url'),
                    data: data,
                    cache: false,
                    beforeSend: function () {
                        $('.booking-btn').attr('disabled', 'disabled');
                    },
                    success: function (data) {
                        $('.total').val(data.total_price);  
                        updateAmountToPay(data.total_price);  
                    },
                    complete: function () {
                        $('.booking-btn').removeAttr('disabled');
                    },
                });
            }
        });

        select2();

        function select2() {
            if ($(".select2").length > 0) {
                $($(".select2")).each(function (index, element) {
                    var id = $(element).attr('id');
                    new Choices('#' + id, {
                        removeItemButton: true,
                    });
                });
            }
        }
    });
</script> --}}

{{-- <<script>
    $(document).ready(function() {
        $('#apartment_type_id').change(function() {
            const selectedApartmentTypeId = $(this).val();
            $('#room_id').empty().append('<option value="" data-rent-value="0">{{ __('Select Room') }}</option>'); // Clear existing rooms

            if (selectedApartmentTypeId) {
                $.ajax({
                    url: '{{ route("fetch.rooms") }}',
                    type: 'GET',
                    data: { apartment_type_id: selectedApartmentTypeId },
                    success: function(response) {
                        if (response.rooms && response.rooms.length > 0) {
                            response.rooms.forEach(function(room) {
                                $('#room_id').append(`
                                    <option value="${room.id}" data-apartment-type-id="${room.apartment_type_id}" data-rent-value="${room.final_price}">
                                        ${room.room_type}
                                    </option>
                                `);
                            });
                        }
                    },
                    error: function() {
                        console.log('Error fetching rooms.');
                    }
                });
            }
        });

        $('#room_id').change(function () {
            const selectedOption = this.options[this.selectedIndex];
            const totalRent = parseFloat(selectedOption.getAttribute('data-rent-value')) || 0;

            // Update the total field
            $('.total').val(totalRent.toFixed(2));
            updateAmountToPay(totalRent);  // Call to update amount to pay
        });

        $('#discountCheckbox').change(function () {
            const totalRent = parseFloat($('.total').val()) || 0;  // Get the total rent
            updateAmountToPay(totalRent);  // Call to update amount to pay
        });

        function updateAmountToPay(totalRent) {
            const originalRent = parseFloat($('.total').val()) || 0; // Store the original rent value
            let amountToPay = totalRent; // Start with total rent
            let discountMessage = $('#discountMessage');

            // Check if the discount checkbox is checked
            if ($('#discountCheckbox').is(':checked')) {
                const discount = totalRent * 0.05; // Calculate 5% discount
                amountToPay = totalRent - discount; 
                discountMessage.text(`$${discount.toFixed(2)} saved`).show();  
            } else {
                discountMessage.hide();  
                amountToPay = totalRent; // Reset to total rent when discount is unchecked
            }

            // Update the amount to pay input
            $('.amount-to-pay').val(amountToPay.toFixed(2)); 
        }

        $('.check_date, .room').change(function() {
            const date1 = $('.check_in').val();
            const date2 = $('.check_out').val();
            const totalRooms = $('.room').val();
            const roomId = $('#room_id').val();

            if (date1 && date2 && totalRooms && roomId) {
                const data = {
                    room: roomId,
                    total_room: totalRooms,
                    date1: date1,
                    date2: date2
                };

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "get",
                    url: $('.check_date').attr('data-url'),
                    data: data,
                    cache: false,
                    beforeSend: function () {
                        $('.booking-btn').attr('disabled', 'disabled');
                    },
                    success: function (data) {
                        $('.total').val(data.total_price);  
                        updateAmountToPay(data.total_price);  // Update amount to pay based on fetched total price
                    },
                    complete: function () {
                        $('.booking-btn').removeAttr('disabled');
                    },
                });
            }
        });

        select2();

        function select2() {
            if ($(".select2").length > 0) {
                $($(".select2")).each(function (index, element) {
                    var id = $(element).attr('id');
                    new Choices('#' + id, {
                        removeItemButton: true,
                    });
                });
            }
        }
    });
</script> --}}

{{-- <script>
    $(document).ready(function() {
        $('#apartment_type_id').change(function() {
            const selectedApartmentTypeId = $(this).val();
            $('#room_id').empty().append('<option value="" data-rent-value="0">{{ __('Select Room') }}</option>'); // Clear existing rooms

            if (selectedApartmentTypeId) {
                $.ajax({
                    url: '{{ route("fetch.rooms") }}',
                    type: 'GET',
                    data: { apartment_type_id: selectedApartmentTypeId },
                    success: function(response) {
                        if (response.rooms && response.rooms.length > 0) {
                            response.rooms.forEach(function(room) {
                                $('#room_id').append(`
                                    <option value="${room.id}" data-apartment-type-id="${room.apartment_type_id}" data-rent-value="${room.final_price}">
                                        ${room.room_type}
                                    </option>
                                `);
                            });
                        }
                    },
                    error: function() {
                        console.log('Error fetching rooms.');
                    }
                });
            }
        });

        $('#room_id').change(function () {
            const selectedOption = this.options[this.selectedIndex];
            const totalRent = parseFloat(selectedOption.getAttribute('data-rent-value')) || 0;

            // Update the sub_total field
            $('.sub_total').val(totalRent.toFixed(2));  // Change from .total to .sub_total
            updateAmountToPay(totalRent);  // Call to update amount to pay
        });

        $('#applyDiscountButton').click(function () {
            const totalRent = parseFloat($('.sub_total').val()) || 0;  // Get the sub_total
            const discountAmount = parseFloat($('#discount_amount').val()) || 0;
            const errorMessage = $('#discountErrorMessage');

            errorMessage.hide(); // Hide previous error

            // Validate discount amount
            if (discountAmount < 0) {
                errorMessage.text('Discount cannot be less than 0').show();
                $('#discount_amount').val(0);
            } else if (discountAmount > totalRent) {
                errorMessage.text('Discount cannot be greater than Subtotal').show();  // Update the error message
                $('#discount_amount').val(totalRent.toFixed(2));
            } else {
                updateAmountToPay(totalRent);
            }
        });

        function updateAmountToPay(totalRent) {
            let discountAmount = parseFloat($('#discount_amount').val()) || 0;
            let amountToPay = totalRent - discountAmount;
            let discountMessage = $('#discountMessage');

            if (amountToPay < 0) {
                amountToPay = 0;
            }

            discountMessage.text(`$${discountAmount.toFixed(2)} saved`).show();
            $('.amount-to-pay').val(amountToPay.toFixed(2));
        }

        $('.check_date, .room').change(function() {
            const date1 = $('.check_in').val();
            const date2 = $('.check_out').val();
            const totalRooms = $('.room').val();
            const roomId = $('#room_id').val();

            if (date1 && date2 && totalRooms && roomId) {
                const data = {
                    room: roomId,
                    total_room: totalRooms,
                    date1: date1,
                    date2: date2
                };

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "get",
                    url: $('.check_date').attr('data-url'),
                    data: data,
                    cache: false,
                    beforeSend: function () {
                        $('.booking-btn').attr('disabled', 'disabled');
                    },
                    success: function (data) {
                        $('.sub_total').val(data.total_price);  // Change from .total to .sub_total
                        updateAmountToPay(data.total_price);  // Update amount to pay based on fetched total price
                    },
                    complete: function () {
                        $('.booking-btn').removeAttr('disabled');
                    },
                });
            }
        });

        select2();

        function select2() {
            if ($(".select2").length > 0) {
                $($(".select2")).each(function (index, element) {
                    var id = $(element).attr('id');
                    new Choices('#' + id, {
                        removeItemButton: true,
                    });
                });
            }
        }
    });
</script> --}}


<script>
    document.getElementById('apartment_type_id').addEventListener('change', function () {
        const selectedApartmentTypeId = this.value;
        const roomSelect = document.getElementById('room_id');
        
        // Show only rooms that match the selected apartment type
        Array.from(roomSelect.options).forEach(option => {
            if (option.value === "") {
                option.hidden = false; // Keep the placeholder option visible
                option.selected = true; // Select the placeholder option by default
            } else {
                const roomApartmentTypeId = option.getAttribute('data-apartment-type-id');
                option.hidden = roomApartmentTypeId !== selectedApartmentTypeId;
            }
        });
    });
    $(document).ready(function() {

        $('#room_id').change(function () {
            const selectedOption = this.options[this.selectedIndex];
            const totalRent = parseFloat(selectedOption.getAttribute('data-rent-value'));

            $('.sub_total').val(totalRent.toFixed(2));
            updateAmountToPay(totalRent);  
        });

        $('#applyDiscountButton').click(function () {
            const totalRent = parseFloat($('.sub_total').val()) || 0;
            const discountAmount = parseFloat($('#discount_amount').val()) || 0;
            const errorMessage = $('#discountErrorMessage');

            errorMessage.hide();

            if (discountAmount < 0) {
                errorMessage.text('Discount cannot be less than 0').show();
                $('#discount_amount').val(0);
            } else if (discountAmount > totalRent) {
                errorMessage.text('Discount cannot be greater than Total Rent').show();
                $('#discount_amount').val(totalRent.toFixed(2));
            } else {
                updateAmountToPay(totalRent);
            }

            updateAmountToPay(totalRent);  
        });

        function updateAmountToPay(totalRent) {
            let discountAmount = parseFloat($('#discount_amount').val()) || 0;
            let amountToPay = totalRent - discountAmount;

            if (amountToPay < 0) {
                amountToPay = 0;
            }

            $('.amount-to-pay').val(amountToPay.toFixed(2)); 
        }

        $('.check_date, .room').change(function() {
            const date1 = $('.check_in').val();
            const date2 = $('.check_out').val();
            const totalRooms = $('.room').val();
            const roomId = $('#room_id').val();

            if (date1 && date2 && totalRooms && roomId) {
                const data = {
                    room: roomId,
                    total_room: totalRooms,
                    date1: date1,
                    date2: date2
                };

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "get",
                    url: $('.check_date').attr('data-url'),
                    data: data,
                    cache: false,
                    beforeSend: function () {
                        $('.booking-btn').attr('disabled', 'disabled');
                    },
                    success: function (data) {
                        $('.total').val(data.total_price);  
                        updateAmountToPay(data.total_price);  
                    },
                    complete: function () {
                        $('.booking-btn').removeAttr('disabled');
                    },
                });
            }
        });

        select2();

        function select2() {
            if ($(".select2").length > 0) {
                $($(".select2")).each(function (index, element) {
                    var id = $(element).attr('id');
                    new Choices('#' + id, {
                        removeItemButton: true,
                    });
                });
            }
        }
    });
</script>