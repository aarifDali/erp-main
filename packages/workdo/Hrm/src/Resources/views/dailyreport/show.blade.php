@extends('layouts.main')
@section('page-title')
    {{ __('Employee') }}
@endsection
@section('page-breadcrumb')
    {{ __('Employee') }}
@endsection

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            @if (Auth::user()->type == 'company')
                                <a href="#useradd-0"
                                    class="list-group-item list-group-item-action border-0 active">{{ _("Overview") }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div>
                                </a>
                            @endif                            
                            <a href="#useradd-1"
                                class="list-group-item list-group-item-action border-0">
                                {{ \Carbon\Carbon::parse($dailyReport->report_date)->format('d-m-Y, l') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i>
                                </div>
                            </a>                              
                            <a href="#useradd-4"
                                class="list-group-item list-group-item-action border-0">{{ __('Remarks') }} <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>                        
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    @if (Auth::user()->type == 'company')
                        <div id="useradd-0">
                            <ul class="nav nav-pills nav-fill cust-nav information-tab" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="item_details" data-bs-toggle="pill"
                                        data-bs-target="#details-tab" type="button">{{ __('Staff Details') }}</button>
                                </li>
                            </ul>
                            <div class="tab-content mt-3" id="pills-tabContent">
                                <div class="tab-pane fade active show" id="details-tab" role="tabpanel" aria-labelledby="pills-user-tab-1">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row mt-4">                                            
                                                <dt class="col-lg-4 h6 text-lg">{{ __('Name') }}
                                                </dt>
                                                <dd class="col-lg-8 text-lg">
                                                    {{ $dailyReport->employees->name }}
                                                </dd>
                                                <dt class="col-lg-4 h6 text-lg">{{ __('Department') }}
                                                </dt>
                                                <dd class="col-lg-8 text-lg">
                                                    {{ $employee->branch->name ?? __('Not Assigned') }}
                                                </dd>
                                                <dt class="col-lg-4 h6 text-lg">{{ __('Designation') }}
                                                </dt>
                                                <dd class="col-lg-8 text-lg">
                                                    {{ $employee->designation->name ?? __('Not Assigned') }}
                                                </dd>
                                                <dt class="col-lg-4 h6 text-lg">{{ __('Branch') }}
                                                </dt>
                                                <dd class="col-lg-8 text-lg">
                                                    {{ $employee->branch->name ?? __('Not Assigned') }}
                                                </dd>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>   
                    @endif                                     
                    <div id="useradd-1">
                        <div id="item_vendor">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-11">
                                                <h5 class="m-0">
                                                    {{ __('Task Summary') }}
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="tab-content" id="pills-tabContent">
                                            <div class="tab-pane fade active show" id="details-tab" role="tabpanel" aria-labelledby="pills-user-tab-1">
                                                <div class="row">
                                                    @foreach ($dailyReport->tasks as $index => $task)
                                                        <div class="col-lg-12">
                                                            <div class="card">
                                                                <div class="card-body">                                                                                      
                                                                    <div class="row">
                                                                        <h5 class="col-lg-1 mb-3">#{{ $loop->iteration }}</h5>
                                                                        <!-- Attachment Section -->
                                                                        <div class="col-lg-3 text-center">
                                                                            @php
                                                                                $path = check_file($task->attachment) ? get_file($task->attachment) : asset('storage/app/public/attachments/' . $task->attachment);
                                                                            @endphp
                    
                                                                            @if ($task->attachment)
                                                                                <a href="{{ $path }}" data-bs-toggle="modal" data-bs-target="#imageModal-{{ $index }}">
                                                                                    <img class="img_setting seo_image" src="{{ $path }}" alt="Task Attachment">
                                                                                </a>
                                                                                <h6 class="mt-3">{{ __('Attachment') }}</h6>
                                                                            @else
                                                                                {{-- <p>{{ __('No file Attached') }}</p> --}}
                                                                                <h6 class="mt-5">{{ __('No Attachment') }}</h6>
                                                                            @endif                  
                                                                            
                                                                        </div>                    
                                                                        <!-- Details Section -->
                                                                        <div class="col-lg-8">
                                                                            <dl class="row mt-2">
                                                                                <dt class="col-lg-4 h6 text-lg">{{ __('Description') }}</dt>
                                                                                <dd class="col-lg-8 text-lg">{{ $task->description }}</dd>
                    
                                                                                <dt class="col-lg-4 h6 text-lg">{{ __('Time Spent') }}</dt>
                                                                                <dd class="col-lg-8 text-lg">
                                                                                    @if ($task->start_time && $task->end_time)
                                                                                        {{ \Carbon\Carbon::parse($task->start_time)->format('H:i') }} to {{ \Carbon\Carbon::parse($task->end_time)->format('H:i') }}
                                                                                    @elseif ($task->start_time && !$task->end_time)
                                                                                        {{ \Carbon\Carbon::parse($task->start_time)->format('H:i') }} to --
                                                                                    @else
                                                                                        -- to --
                                                                                    @endif
                                                                                </dd>
                                                                                
                    
                                                                                <dt class="col-lg-4 h6 text-lg">{{ __('Status') }}</dt>
                                                                                <dd class="col-lg-8 text-lg">{{ ucfirst($task->status) }}</dd>
                                                                            </dl>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>                    
                                                        <!-- Modal for Enlarged Image -->
                                                        <div class="modal fade" id="imageModal-{{ $index }}" tabindex="-1" aria-labelledby="imageModalLabel-{{ $index }}" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-xl">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="imageModalLabel-{{ $index }}">{{ __('Attachment') }}</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body text-center">
                                                                        <img class="img-fluid" src="{{ $path }}" alt="Task Attachment" style="max-height: 80vh;">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                    <div id="useradd-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Remarks') }}</h5>
                            </div>
                            <div class="card-body">
                                {{-- <div class="row">
                                    <div class="card"> --}}
                                        <div class="card-body">  
                                            {{ $dailyReport->remarks }} 
                                        </div>       
                                    {{-- </div>       
                                </div>        --}}
                            </div>       
                        </div>       
                    </div>       
                </div>                
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dropzone-amd-module.min.js') }}"></script>
    <script>
        function changetab(tabname) {
            var someTabTriggerEl = document.querySelector('button[data-bs-target="' + tabname + '"]');
            var actTab = new bootstrap.Tab(someTabTriggerEl);
            actTab.show();
        }
    </script>
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })
    </script>
@endpush