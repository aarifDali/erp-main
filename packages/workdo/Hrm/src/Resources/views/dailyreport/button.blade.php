@if (Auth::user()->type == 'staff')
    <div class="action-btn  me-2">
        <a href="{{ route('daily-report.edit',$daily_report->id) }}"
            class="mx-3 btn btn-sm bg-info align-items-center" data-bs-toggle="tooltip" title=""
            data-bs-original-title="{{ __('Edit') }}">
            <i class="ti ti-pencil text-white"></i>
        </a>
    </div>
    <div class="action-btn me-2">
        {{ Form::open(['route' => ['daily-report.destroy', $daily_report->id], 'class' => 'm-0']) }}
        @method('DELETE')
        <a class="mx-3 btn btn-sm bg-danger align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip"
            title="{{ __('Delete') }}" data-bs-original-title="Delete" aria-label="Delete"
            data-confirm="{{ __('Are You Sure?') }}"
            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
            data-confirm-yes="delete-form-{{ $daily_report->id }}"><i
                class="ti ti-trash text-white text-white"></i></a>
        {{ Form::close() }}
    </div>    
{{-- @elseif (Auth::user()->type == 'company')
    <div class="action-btn me-2">
        <a class="mx-3 btn bg-warning btn-sm  align-items-center" href="{{ route('daily-report.edit', $daily_report->id ) }}"
            data-bs-original-title="{{ __('View') }}">
            <i class="ti ti-eye text-white"></i>
        </a>
        <a href=""></a>
    </div> --}}
@endif    

@if (Auth::user()->type == 'company' || Auth::user()->type == 'staff')
    <div class="action-btn  me-2">
        <a href="{{ route('daily-report.show', $daily_report->id) }}"
            class="mx-3 btn btn-sm bg-warning align-items-center" data-bs-toggle="tooltip" title=""
            data-bs-original-title="{{ __('Show') }}">
            <i class="ti ti-eye text-white"></i>
        </a>
    </div>
@endif