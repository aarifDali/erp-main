
@if ($project->is_active)
    @permission('task manage')
        <div class="action-btn me-2">
            <a data-size="md" href="{{ route('projects.task.board', [$project->id]) }}"
                class="btn btn-sm align-items-center text-white bg-success" data-bs-toggle="tooltip"
                data-title="{{ __('Task Board') }}" title="{{ __('Task Board') }}"><i class="ti ti-file-text"></i></a>
        </div>
    @endpermission
    @if (module_is_active('ProjectTemplate'))
        @permission('project template create')
            <div class="action-btn me-2">
                <a data-size="md"
                    data-url="{{ route('project-template.create', ['project_id' => $project->id, 'type' => 'template']) }}"
                    class="btn btn-sm align-items-center text-white bg-primary" data-ajax-popup="true"
                    data-bs-toggle="tooltip" data-title="{{ __('Save as template') }}" title="{{ __('Save as template') }}"><i
                        class="ti ti-bookmark"></i></a>
                </a>
            </div>
        @endpermission
    @endif
    @permission('project create')
        <div class="action-btn me-2">
            <a data-size="md" data-url="{{ route('project.copy', [$project->id]) }}"
                class="btn btn-sm align-items-center text-white bg-secondary" data-ajax-popup="true" data-bs-toggle="tooltip"
                data-title="{{ __('Duplicate Project') }}" title="{{ __('Duplicate') }}"><i class="ti ti-copy"></i></a>
        </div>
    @endpermission
    @permission('project show')
        <div class="action-btn me-2">
            <a href="{{ route('projects.show', $project->id) }}" data-bs-toggle="tooltip" title="{{ __('Details') }}"
                data-title="{{ __('Project Details') }}" class="mx-3 btn btn-sm align-items-center text-white bg-warning">
                <i class="ti ti-eye"></i>
            </a>
        </div>
    @endpermission
    @permission('project edit')
        <div class="action-btn me-2">
            <a data-size="md" data-url="{{ route('projects.edit', $project->id) }}"
                class="btn btn-sm align-items-center text-white bg-info" data-ajax-popup="true" data-bs-toggle="tooltip"
                data-title="{{ __('Project Edit') }}" title="{{ __('Edit') }}"><i class="ti ti-pencil"></i></a>
        </div>
    @endpermission
    @permission('project delete')
        <div class="action-btn">
            {!! Form::open(['method' => 'DELETE', 'route' => ['projects.destroy', $project->id]]) !!}
            <a href="#!" class="btn btn-sm   align-items-center text-white show_confirm bg-danger" data-bs-toggle="tooltip"
                title='Delete'>
                <i class="ti ti-trash"></i>
            </a>
            {!! Form::close() !!}
        </div>
    @endpermission
@else
    <div class="action-btn">
        <span class="btn bg-dark btn-sm align-items-center"><i class="ti ti-lock text-white"></i></span>
    </div>
@endif
