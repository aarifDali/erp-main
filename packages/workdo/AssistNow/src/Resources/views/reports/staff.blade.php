@extends('layouts.main')

@section('page-title')
    {{ __('Staff Reports') }}
@endsection

@section('page-breadcrumb')
    {{ __('Staff Reports') }}
@endsection

@section('content')
<div class="container mt-2">    
    <form method="GET" action="{{ route('staff-reports.index') }}">
        <div class="row mb-3">
            <div class="col-md-3">
                <label>Start Date:</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label>End Date:</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-3">
                <label>Employee:</label>
                <select name="employee_id" class="form-control">
                    <option value="">All Employees</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary mt-4">Filter</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Service Date</th>
                <th>Employee</th>
                <th>Service</th>
                <th>Fund Recieved</th>
                <th>Total Charge</th>
                
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $task)
                <tr>
                    <td>{{ $task->service_date->format('d-m-Y') }}</td>
                    <td>{{ $task->employee ? $task->employee->name : 'N/A' }}</td>
                    <td>{{ $task->service ? $task->service->name : 'N/A' }}</td>
                    <td>€ {{ $task->fund_recieved }}</td>
                    <td>€ {{ $task->total_charge }}</td>
                    
                </tr>
            @empty
                <tr><td colspan="4">No data available</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Total</th>
                <th>€ {{ number_format($totalFundReceived, 2) }}</th>
                <th>€ {{ number_format($totalCharge, 2) }}</th>
            </tr>
            {{-- <tr>
                <th colspan="3">Profit</th>
                <th colspan="2">
                    <span style="color: {{ $profit >= 0 ? 'green' : 'red' }}; font-weight: bold;">
                        {{ $profit >= 0 ? '+ ' : '- ' }}€ {{ number_format(abs($profit), 2) }}
                    </span>
                </th>
            </tr> --}}
        </tfoot>
    </table>
    <div class="text-center mt-3">
        <h4 style="font-size: 1.5rem; font-weight: bold; color: {{ $profit >= 0 ? 'green' : 'red' }};">
            Total Revenue: {{ $profit >= 0 ? '+ ' : '- ' }}€ {{ number_format(abs($profit), 2) }}
        </h4>
    </div>
</div>
@endsection
