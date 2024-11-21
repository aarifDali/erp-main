@extends('layouts.main')
@section('page-title')
    {{__('Manage Roles')}}
@endsection
@section('page-breadcrumb')
{{ __('Roles') }}
@endsection
@section('page-action')
    @permission('roles create')
        <div>
            <a href="#" class="btn btn-sm btn-primary" data-url="{{ route('roles.create') }}" data-size="xl" data-bs-toggle="tooltip"  data-bs-original-title="{{ __('Create') }}" data-ajax-popup="true" data-title="{{__('Create New Role')}}">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endpermission
@endsection
@push('css')
    @include('layouts.includes.datatable-css')
@endpush
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    {{ $dataTable->table(['width' => '100%']) }}
                </div>
            </div>
        </div>
    </div>
 </div>
@endsection
@push('scripts')
@include('layouts.includes.datatable-js')
{{ $dataTable->scripts() }}
    <script>
        function Checkall(module = null) {
            var ischecked = $("#checkall-"+module).prop('checked');
            if(ischecked == true)
            {
                $('.checkbox-'+module).prop('checked',true);
            }
            else
            {
                $('.checkbox-'+module).prop('checked',false);
            }
        }
    </script>
    <script type="text/javascript">
        function CheckModule(cl = null)
        {
            var ischecked = $("#"+cl).prop('checked');
            if(ischecked == true)
            {
                $('.'+cl).find("input[type=checkbox]").prop('checked',true);
            }
            else
            {
                $('.'+cl).find("input[type=checkbox]").prop('checked',false);
            }
        }
    </script>
@endpush
