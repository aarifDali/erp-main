@extends('layouts.main')

@section('page-title', __('Manage Services'))

@section('page-action')
<div class="d-flex">
    @stack('addButtonHook')
    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-end">
        <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create Service') }}"
            data-url="{{ route('assistnow-services.create') }}" data-toggle="tooltip" title="{{ __('Create') }}">
            <i class="ti ti-plus text-white"></i>
        </a>
    </div>
</div>
@endsection
@section('page-breadcrumb')
    {{ __('Services') }}
@endsection

@push('css')
    <style>
        .amenities .preview-img{
            width:300px;
            height:200px;
            object-fit:contain;
        }

        @media only screen and (max-width: 991px){
            .iconpicker{
                justify-content: start !important;
            }
        }
        @media only screen and (max-width: 575px){
            .card .card-header .card-header-right {
                display: block;
            }
            .amenities .preview-img{
                width:260px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="row">
        @foreach ($services as $i => $service)
            <div class="col-xl-4 col-sm-6 d-flex">
                <div class="card grid-card text-center w-100">
                    <div class="card-header border-0 pb-0 p-3">
                       <div class="d-flex justify-content-between">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="amenities-title text-white bg-primary">
                                    {{ $service->name }}
                                </div>
                            </div>
                            {{-- @if (\Auth::user()->isAbleTo('edit services') || \Auth::user()->isAbleTo('services delete')) --}}
                            <div class="card-header-right">
                                <div class="btn-group card-option">
                                    <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-end">
                                        {{-- @permission('services edit') --}}
                                        <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="md"
                                            data-title="{{ __('Edit Service') }}"
                                            data-url="{{ route('assistnow-services.edit', $service->id) }}" data-toggle="tooltip"
                                            title="{{ __('Edit') }}">
                                            <i class="ti ti-pencil" style="font-size: 18px;margin-right: 10px;"></i>
                                            <span>{{__('Edit')}}</span>
                                        </a>
                                        {{-- @endpermission
                                        @permission('services delete') --}}
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => ['assistnow-services.destroy', $service->id],
                                            'id' => 'delete-form-' . $service->id,
                                        ]) !!}

                                        <a class="dropdown-item show_confirm text-danger" data-bs-toggle="tooltip" title=""
                                            data-bs-original-title="Delete" aria-label="Delete"
                                            data-confirm="{{ __('Are You Sure?') }}"
                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                            data-confirm-yes="delete-form-{{ $service->id }}"> <i class="ti ti-trash" style="font-size: 18px;margin-right: 10px;"></i>
                                            <span> {{__('Delete')}} </span></a>
                                        {{ Form::close() }}
                                        {{-- @endpermission --}}
                                    </div>
                                </div>
                            </div>
                            {{-- @endif --}}
                       </div>
                    </div>
                    <div class="card-body roles-content-top p-3">
                        <div class="d-flex flex-wrap gap-2">
                            <div class="badge p-2  px-3 text-black">
                                <strong>Billing Time:</strong> {{ $service->billing_interval }} mins
                                
                            </div>   
                            @if (!empty($service->description))
                                    <div class="badge p-2 px-3 text-black">
                                        <strong>Description:</strong> {{ $service->description }}
                                    </div>
                                @endif                                                         
                        </div>                        
                    </div>                    
                </div>
            </div>
        @endforeach
    </div>
@endsection
