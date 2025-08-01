@extends('layouts.main')
@section('page-title')
    {{ __('Edit Client') }}
@endsection
@section('page-breadcrumb')
    {{ __('Client') }}
@endsection

@section('content')
<div class="row">
    {{ Form::open(['url' => route('assistnow-clients.update', $client->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'repeater needs-validation', 'novalidate' ]) }}

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!-- Client ID -->
                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            {{ Form::label('client_id', __('Client ID'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('client_id', $client->client_id, ['class' => 'form-control', 'placeholder' => 'Enter Unique Client ID', 'required' => 'required']) }}
                        </div>
                    </div>

                    <!-- Name -->
                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            {{ Form::label('name', __('Client Name'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::text('name', $client->name, ['class' => 'form-control', 'placeholder' => 'Enter Client Name', 'required' => 'required']) }}
                        </div>
                    </div>

                    <!-- Debtor -->
                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            {{ Form::label('debtor_id', __('Debtor'), ['class' => 'form-label']) }}<x-required></x-required>
                            <select class="form-control" name="debtor_id" id="debtor_id" required>
                                <option value="" data-rent-value="0">{{ __('Select Debtor') }}</option>
                                @foreach($debtors as $debtor)
                                    <option value="{{ $debtor->id }}" {{ $client->debtor_id == $debtor->id ? 'selected' : '' }}>
                                        {{ $debtor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            <x-mobile name="phone" label="{{ __('Phone') }}"
                                placeholder="{{ __('Enter Client Phone') }}" id="phone" :value="$client->phone" required>
                            </x-mobile>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-lg-6 col-12">
                        <div class="form-group">
                            {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}<x-required></x-required>
                            {{ Form::email('email', $client->email, ['class' => 'form-control', 'placeholder' => 'Enter Email', 'required' => 'required']) }}
                        </div>
                    </div>              
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <h5 class="h4 d-inline-block font-weight-400 mb-4">{{ __('Client Contacts') }}</h5>
                    <div id="contact-repeater">
                        @foreach($client->relations as $index => $relation)
                            <div class="contact-group row">
                                <div class="col-lg-3 col-12">
                                    <div class="form-group">
                                        {{ Form::text("contacts[$index][contact_name]", $relation->contact_name, ['class' => 'form-control', 'placeholder' => 'Contact Name', 'required' => 'required']) }}
                                    </div>
                                </div>
    
                                <div class="col-lg-3 col-12">
                                    <div class="form-group">
                                        {{ Form::text("contacts[$index][relationship]", $relation->relationship, ['class' => 'form-control', 'placeholder' => 'Relationship', 'required' => 'required']) }}
                                    </div>
                                </div>
    
                                <div class="col-lg-2 col-12">
                                    <div class="form-group">
                                        {{ Form::text("contacts[$index][phone]", $relation->phone, ['class' => 'form-control', 'placeholder' => 'Phone', 'required' => 'required']) }}
                                    </div>
                                </div>
    
                                <div class="col-lg-2 col-12">
                                    <div class="form-group">
                                        {{ Form::text("contacts[$index][phone_2]", $relation->phone_2, ['class' => 'form-control', 'placeholder' => 'Phone 2']) }}
                                    </div>
                                </div>
    
                                <div class="col-lg-2 col-12">
                                    <div class="form-group">
                                        {{ Form::text("contacts[$index][phone_extra]", $relation->phone_extra, ['class' => 'form-control', 'placeholder' => 'Extra Phone']) }}
                                    </div>
                                </div>
    
                                <div class="col-lg-3 col-12">
                                    <div class="form-group">
                                        {{ Form::email("contacts[$index][email]", $relation->email, ['class' => 'form-control', 'placeholder' => 'Email', 'required' => 'required']) }}
                                    </div>
                                </div>
    
                                <div class="col-lg-1 col-12">
                                    <button type="button" class="btn btn-danger remove-contact">X</button>
                                </div>
                            </div>
                        @endforeach
                    </div>    
                    <div class="item-section py-2">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                                <div class="all-button-box me-2">
                                    <button type="button" class="btn btn-success" id="add-contact">{{ __('+ Add Contact') }}</button>
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
            onclick="location.href = '{{ route('assistnow-clients.index') }}';" class="btn btn-light me-2">
        <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary" id="submit">
    </div>
    {{ Form::close() }}
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('js/jquery-searchbox.js') }}"></script>
    <script>
        $(document).ready(function () {
            let contactIndex = 1;

            $("#add-contact").click(function () {
                let newContact = `
                    <div class="contact-group row">
                        <div class="col-lg-3 col-12">
                            <div class="form-group">
                                <input type="text" name="contacts[${contactIndex}][contact_name]" class="form-control" placeholder="Contact Name" required>
                            </div>
                        </div>

                        <div class="col-lg-3 col-12">
                            <div class="form-group">
                                <input type="text" name="contacts[${contactIndex}][relationship]" class="form-control" placeholder="Relationship" required>
                            </div>
                        </div>

                        <div class="col-lg-2 col-12">
                            <div class="form-group">
                                <input type="text" name="contacts[${contactIndex}][phone]" class="form-control" placeholder="Phone" required>
                            </div>
                        </div>

                        <div class="col-lg-2 col-12">
                            <div class="form-group">
                                <input type="text" name="contacts[${contactIndex}][phone_2]" class="form-control" placeholder="Phone 2">
                            </div>
                        </div>

                        <div class="col-lg-2 col-12">
                            <div class="form-group">
                                <input type="text" name="contacts[${contactIndex}][phone_extra]" class="form-control" placeholder="Extra Phone">
                            </div>
                        </div>

                        <div class="col-lg-3 col-12">
                            <div class="form-group">
                                <input type="email" name="contacts[${contactIndex}][email]" class="form-control" placeholder="Email" required>
                            </div>
                        </div>

                        <div class="col-lg-1 col-12">
                            <button type="button" class="btn btn-danger remove-contact">X</button>
                        </div>
                    </div>
                `;

                $("#contact-repeater").append(newContact);
                contactIndex++;
            });

            $(document).on("click", ".remove-contact", function () {
                $(this).closest(".contact-group").remove();
            });
        });
    </script>
@endpush