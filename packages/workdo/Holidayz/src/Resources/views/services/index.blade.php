@extends('layouts.main')

@section('page-title', __('Manage Amenities'))

@section('page-action')
@stack('addButtonHook')
    @permission('services create')
        <div class="text-end d-flex all-button-box justify-content-md-end justify-content-end">
            <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create New Amenities') }}"
                data-url="{{ route('hotel-services.create') }}" data-toggle="tooltip" title="{{ __('Create') }}">
                <i class="ti ti-plus text-white"></i>
            </a>
        </div>
    @endpermission
@endsection
@section('page-breadcrumb')
    {{ __('Hotel Management') }},
    {{ __('Amenities') }}
@endsection

@push('css')
    <style>
        @media only screen and (max-width: 575px){
            .card .card-header .card-header-right {
                display: block;
            }
        }
    </style>
@endpush

@section('content')
    <div class="row">
        @foreach ($services as $i => $service)
            <div class="col-md-4 mb-3 d-flex align-items-stretch">
                <div class="card text-center w-100">
                    <div class="card-header border-0 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <div class="badge bg-primary p-2 px-3">
                                    {{ $service->name }}
                                </div>
                            </h6>
                        </div>
                        @if (\Auth::user()->isAbleTo('edit services') || \Auth::user()->isAbleTo('services delete'))
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-end">
                                    @permission('services edit')
                                    <a href="#" class="dropdown-item" data-ajax-popup="true" data-size="lg"
                                        data-title="{{ __('Edit Amenity') }}"
                                        data-url="{{ route('hotel-services.edit', $service->id) }}" data-toggle="tooltip"
                                        title="{{ __('Edit Amenity') }}">
                                        <i class="ti ti-pencil" style="font-size: 18px;margin-right: 10px;"></i>
                                        <span>Edit</span>
                                    </a>
                                    @endpermission
                                    @permission('services delete')
                                    {!! Form::open([
                                        'method' => 'DELETE',
                                        'route' => ['hotel-services.destroy', $service->id],
                                        'id' => 'delete-form-' . $service->id,
                                    ]) !!}

                                    <a class="dropdown-item show_confirm" data-bs-toggle="tooltip" title=""
                                        data-bs-original-title="Delete" aria-label="Delete"
                                        data-confirm="{{ __('Are You Sure?') }}"
                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                        data-confirm-yes="delete-form-{{ $service->id }}"> <i class="ti ti-trash" style="font-size: 18px;margin-right: 10px;"></i>
                                        <span> Delete </span></a>
                                    {{ Form::close() }}
                                    @endpermission
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="card-body full-card">
                        @foreach ($service->getSubServices as $subService)
                            <h5 class="mt-3 text-primary">{{ $subService->name }}</h5>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
