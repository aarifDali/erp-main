{{ Form::open(array('route' => array('bill.createpayment', $bill->id),'method'=>'post','enctype' => 'multipart/form-data', 'class'=>'needs-validation', 'novalidate')) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('date', __('Date'),['class'=>'form-label']) }}<x-required></x-required>
                {{Form::date('date',null,array('class'=>'form-control ','required'=>'required','placeholder'=>'Select Date'))}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::number('amount',$bill->getDue(), array('class' => 'form-control','required'=>'required','min'=>'0','step'=>'0.01')) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('account_id', __('Account'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::select('account_id',$accounts,null, array('class' => 'form-control ','required'=>'required','placeholder'=>'Select Account')) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('reference', __('Reference'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::text('reference',null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'),['class'=>'form-label']) }}<x-required></x-required>
                {{ Form::textarea('description', '', array('class' => 'form-control','required'=>'required','rows'=>3)) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('add_receipt', __('Payment Receipt'), ['class' => 'form-label']) }}
            <div class="choose-files ">
                <label for="add_receipt">
                    <div class=" bg-primary "> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                    <input type="file" class="form-control file" name="add_receipt" id="add_receipt">
                </label>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}
