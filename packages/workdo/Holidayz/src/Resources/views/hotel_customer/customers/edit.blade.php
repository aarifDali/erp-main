{{ Form::model($customer, ['route' => ['hotel-customer.update', $customer->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data','class'=>'needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-4">
            {!! Form::label('', __('First Name'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::text('name', $customer->name, ['class' => 'form-control', 'placeholder' => 'Enter First Name', 'required' => true]) !!}
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('', __('Last Name'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::text('last_name', $customer->last_name, [
                'class' => 'form-control',
                'placeholder' => 'Enter Last Name',
                'required' => true,
            ]) !!}
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('', __('Email'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::email('email', $customer->email, ['class' => 'form-control', 'placeholder' => 'Enter Email', 'required' => true]) !!}
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('', __('Dob'), ['class' => 'form-label']) !!}<x-required></x-required>
            {{ Form::date('dob', $customer->dob, ['class' => 'form-control', 'required' => true]) }}
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="form-group col-md-4">
                    {!! Form::label('', __('Enable'), ['class' => 'form-label']) !!}
                    <div class="form-check form-switch">
                        <input type="hidden" name="is_active" value="0">
                        {!! Form::checkbox('is_active', 1, $customer->is_active, [
                            'class' => 'form-check-input input-primary',
                            'id' => 'customCheckdef1',
                        ]) !!}
                        <label class="form-check-label" for="customCheckdef1"></label>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="form-group col-md-4">
            {!! Form::label('', __('Identification Number'), ['class' => 'form-label']) !!}
            {{ Form::text('id_number', $customer->id_number, ['class' => 'form-control', 'placeholder' => 'Enter Identification Number']) }}
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('', __('Company'), ['class' => 'form-label']) !!}
            {!! Form::text('company', isset($customer->company) ? $customer->company : null, [
                'class' => 'form-control',
                'placeholder' => 'Enter Company Name',
            ]) !!}
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('', __('VAT number'), ['class' => 'form-label']) !!}
            {!! Form::text('vat_number', isset($customer->vat_number) ? $customer->vat_number : null, [
                'class' => 'form-control',
                'placeholder' => 'Enter VAT Number',
            ]) !!}
        </div> --}}
        <div class="form-group col-md-4">
            {!! Form::label('', __('Address alias'), ['class' => 'form-label']) !!}
            {{ Form::text('alias', isset($customer->getAddresses->alias) ? $customer->getAddresses->alias : null, ['class' => 'form-control', 'placeholder' => 'Enter Address alias']) }}
        </div>
        
        <div class="form-group col-md-4">
            {!! Form::label('', __('Address'), ['class' => 'form-label']) !!}
            {{ Form::text('address', isset($customer->getAddresses) ? $customer->getAddresses->address : null, ['class' => 'form-control', 'placeholder' => 'Enter Address']) }}
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('', __('Address 2'), ['class' => 'form-label']) !!}
            {{ Form::text('address_2', isset($customer->getAddresses->address_2) ? $customer->getAddresses->address_2 : null, ['class' => 'form-control', 'placeholder' => 'Enter Address 2']) }}
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('', __('State'), ['class' => 'form-label']) !!}
            {{ Form::text('state', isset($customer->getAddresses->state) ? $customer->getAddresses->state : null, ['class' => 'form-control', 'placeholder' => 'Enter State']) }}
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('', __('City'), ['class' => 'form-label']) !!}
            {{ Form::text('city', isset($customer->getAddresses->city) ? $customer->getAddresses->city : null, ['class' => 'form-control', 'placeholder' => 'Enter City']) }}
        </div>
        {{-- <div class="form-group col-md-4">
            {!! Form::label('', __(' Zip/Postal Code'), ['class' => 'form-label']) !!}
            {{ Form::text('zip_code', isset($customer->getAddresses->zip_code) ? $customer->getAddresses->zip_code : null, ['class' => 'form-control', 'placeholder' => 'Enter Zip/Postal Code']) }}
        </div> --}}

        <x-mobile divClass="col-md-4" class="form-control" name="home_phone" label="{{__('Home phone')}}" placeholder="{{__('Enter Home Phone Number')}}"></x-mobile>
        <x-mobile divClass="col-md-4" class="form-control" name="mobile_phone" label="{{__('Mobile phone')}}" placeholder="{{__('Enter Mobile Number')}}" required></x-mobile>
        <div class="form-group col-md-4">
            {!! Form::label('', __('ID Proof'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::file('id_proof', ['class' => 'form-control', 'accept' => 'application/pdf,image/*']) !!}
        
            @if ($customer->id_proof)
                <small class="form-text text-muted">
                    Current ID Proof: {{ $customer->id_proof }} 
                    <a href="{{ $customer->id_proof_path }}" target="_blank">View</a>
                </small>
            @endif
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('', __('Other'), ['class' => 'form-label']) !!}
            {{ Form::text('other', $customer->other, ['class' => 'form-control', 'placeholder' => 'Enter Other Details']) }}
        </div>
        @if (module_is_active('CustomField') && !$customFields->isEmpty())
            <div class="col-6">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    @include('custom-field::formBuilder', [
                        'fildedata' => $customer->customField,
                    ])
                </div>
            </div>
        @endif
        <div class="modal-footer pb-0">
            <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
            <input type="submit" value="Update" class="btn btn-primary bg-primary">
        </div>
    </div>
</div>
