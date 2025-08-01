@extends('layouts.main')

@section('page-title')
    {{ __('Task Assignments') }}
@endsection

@section('page-breadcrumb')
    {{ __('Task Assignments') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">{{ __('Task Assignments') }}</h4>
                    <a href="{{ route('task-assignments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> {{ __('Create Task Assignment') }}
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('No') }}</th>
                                    <th>{{ __('Employee') }}</th>
                                    <th>{{ __('Client') }}</th>
                                    <th>{{ __('Service') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Time Spent (mins)') }}</th>
                                    <th>{{ __('Service Charge') }}</th>
                                    <th>{{ __('Total Charge') }}</th>                                    
                                    <th>{{ __('Gov Fund') }}</th>                                    
                                    <th class="text-end">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $key => $task)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $task->employee->name }}</td>
                                        <td>{{ $task->client->name }}</td>
                                        <td>{{ $task->service->name }}</td>
                                        <td>{{ $task->service_date->format('d-m-Y') }}</td>
                                        <td>{{ $task->time_spent }} Mins</td>
                                        <td>€ {{ number_format($task->service_charge, 2) }}</td>
                                        <td>€ {{ number_format($task->total_charge, 2) }}</td>
                                        <td>€ {{ number_format($task->fund_recieved, 2) }}</td>
                                        <td class="text-end">                                            
                                            <a href="{{ route('task-assignments.edit', $task->id) }}"
                                                class="mx-0 btn btn-sm bg-info align-items-center" data-bs-toggle="tooltip" title=""
                                                data-bs-original-title="{{ __('Edit') }}">
                                                <i class="ti ti-pencil text-white"></i>
                                            </a>                        
                                            {{ Form::open(['route' => ['task-assignments.destroy', $task->id], 'class' => 'd-inline']) }}
                                                @method('DELETE')
                                                <a class="mx-0 btn btn-sm bg-danger align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip"
                                                    title="{{ __('Delete') }}" data-bs-original-title="Delete" aria-label="Delete"
                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                    data-confirm-yes="delete-form-{{ $task->id }}"><i
                                                        class="ti ti-trash text-white text-white"></i></a>
                                            {{ Form::close() }}
                                        </td>    
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination justify-content-center mt-3">
                        {{ $tasks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-confirm').forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    if (confirm("Are you sure you want to delete this task assignment?")) {
                        this.closest('form').submit();
                    }
                });
            });
        });
    </script>
@endpush
