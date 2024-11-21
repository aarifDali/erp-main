{{ Form::open(['id'=>"customerForm", 'route' => 'hotel-customer.store', 'method' => 'post', 'enctype' => 'multipart/form-data','class'=>'needs-validation','novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-4">
            {!! Form::label('', __('First Name'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter First Name', 'required' => true]) !!}
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('', __('Last Name'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Last Name', 'required' => true]) !!}
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('', __('Email'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Enter Email', 'required' => true]) !!}
        </div>
        {{-- <div class="form-group col-md-4">
            {!! Form::label('', __('Password'), ['class' => 'form-label']) !!}<x-required></x-required>
            {{ Form::text('password', null, ['class' => 'form-control', 'placeholder' => 'Enter Password', 'required' => true]) }}
        </div> --}}

        <div class="form-group col-md-4">
            {!! Form::label('', __('Dob'), ['class' => 'form-label']) !!}<x-required></x-required>
            {{ Form::date('dob', null, ['class' => 'form-control', 'required' => true]) }}
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="form-group col-md-4">
                    {!! Form::label('', __('Enable'), ['class' => 'form-label']) !!}
                    <div class="form-check form-switch">
                        <input type="hidden" name="is_active" value="0">
                        {!! Form::checkbox('is_active', 1, 1, ['class' => 'form-check-input input-primary', 'id' => 'customCheckdef1']) !!}
                        <label class="form-check-label" for="customCheckdef1"></label>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="form-group col-md-4">
            {!! Form::label('', __('Identification Number'), ['class' => 'form-label']) !!}
            {{ Form::text('id_number', null, ['class' => 'form-control', 'placeholder' => 'Enter Identification Number']) }}
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('', __('Company'), ['class' => 'form-label']) !!}
            {!! Form::text('company', null, ['class' => 'form-control', 'placeholder' => 'Enter Company Name']) !!}
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('', __('VAT number'), ['class' => 'form-label']) !!}
            {!! Form::text('vat_number', null, ['class' => 'form-control', 'placeholder' => 'Enter VAT Number']) !!}
        </div> --}}
        <div class="form-group col-md-4">
            {!! Form::label('', __('Address alias'), ['class' => 'form-label']) !!}
            {{ Form::text('alias', null, ['class' => 'form-control', 'placeholder' => 'Enter Address alias']) }}
        </div>
        
        <div class="form-group col-md-4">
            {!! Form::label('', __('Address'), ['class' => 'form-label']) !!}
            {{ Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'Enter Address']) }}
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('', __('Address 2'), ['class' => 'form-label']) !!}
            {{ Form::text('address_2', null, ['class' => 'form-control', 'placeholder' => 'Enter Address 2']) }}
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('', __('State'), ['class' => 'form-label']) !!}
            {{ Form::text('state', null, ['class' => 'form-control', 'placeholder' => 'Enter State']) }}
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('', __('City'), ['class' => 'form-label']) !!}
            {{ Form::text('city', null, ['class' => 'form-control', 'placeholder' => 'Enter City']) }}
        </div>
        {{-- <div class="form-group col-md-4">
            {!! Form::label('', __(' Zip/Postal Code'), ['class' => 'form-label']) !!}
            {{ Form::text('zip_code', null, ['class' => 'form-control', 'placeholder' => 'Enter Zip/Postal Code']) }}
        </div> --}}
        <x-mobile divClass="col-md-4" class="form-control" name="home_phone" label="{{__('Home phone')}}" placeholder="{{__('Enter Home Phone Number(Optional)')}}"></x-mobile>
        <x-mobile divClass="col-md-4" class="form-control" name="mobile_phone" label="{{__('Mobile phone')}}" placeholder="{{__('Enter Mobile Number')}}" required></x-mobile>
        <div class="form-group col-md-4">
            {!! Form::label('', __('ID Proof'), ['class' => 'form-label']) !!}<x-required></x-required>
            {!! Form::file('id_proof', ['class' => 'form-control', 'accept' => 'application/pdf,image/*']) !!}
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('', __('Other'), ['class' => 'form-label']) !!}
            {{ Form::text('other', null, ['class' => 'form-control', 'placeholder' => 'Enter Other Details' ]) }}
        </div>
        @if (module_is_active('CustomField') && !$customFields->isEmpty())
            <div class="col-6">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    @include('custom-field::formBuilder')
                </div>
            </div>
        @endif
        <div class="modal-footer pb-0">
            <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
            <input type="submit" value="Create" class="btn btn-primary bg-primary">
        </div>
    </div>
</div>

{!! Form::close() !!}
