@extends('layout')

@section('title')
    {{ get_label('lead_forms', 'Lead Forms') }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{ url('home') }}">{{ get_label('home', 'Home') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        {{ get_label('leads_management', 'Leads Management') }}
                    </li>
                    <li class="breadcrumb-item active">
                        {{ get_label('lead_forms', 'Lead Forms') }}
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('lead-forms.create') }}">
                <button type="button" class="btn btn-sm btn-primary action_create_items" data-bs-toggle="tooltip"
                        data-bs-placement="right"
                        data-bs-original-title="{{ get_label('create_lead_form', 'Create Lead Form') }}">
                    <i class="bx bx-plus"></i>
                </button>
            </a>
        </div>
    </div>

    @if($forms->count() > 0)
        @php
            $visibleColumns = getUserPreferences('lead_forms');
        @endphp

        <div class="card">
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <input type="hidden" id="data_type" value="lead-forms">
                    <input type="hidden" id="save_column_visibility">

                    <table id="table" data-toggle="table"
                           data-url="{{ route('lead-forms.list') }}"
                           data-icons-prefix="bx" data-icons="icons"
                           data-show-refresh="true"
                           data-total-field="total"
                           data-trim-on-search="false"
                           data-data-field="rows"
                           data-page-list="[5, 10, 20, 50, 100, 200]"
                           data-search="true"
                           data-side-pagination="server"
                           data-show-columns="true"
                           data-pagination="true"
                           data-sort-name="created_at"
                           data-sort-order="desc"
                           data-mobile-responsive="true"
                           data-query-params="queryParamsLeadForms">
                        <thead>
                            <tr>
                                <th data-checkbox="true"></th>
                                <th data-field="id"
                                    data-visible="{{ in_array('id', $visibleColumns) || empty($visibleColumns) ? 'true' : 'false' }}"
                                    data-sortable="true">{{ get_label('id', 'ID') }}</th>
                                <th data-field="title"
                                    data-visible="{{ in_array('title', $visibleColumns) || empty($visibleColumns) ? 'true' : 'false' }}"
                                    data-sortable="true">{{ get_label('title', 'Title') }}</th>
                                <th data-field="description"
                                    data-visible="{{ in_array('description', $visibleColumns) || empty($visibleColumns) ? 'true' : 'false' }}"
                                    data-sortable="true">{{ get_label('description', 'Description') }}</th>
                                <th data-field="source"
                                    data-visible="{{ in_array('source', $visibleColumns) || empty($visibleColumns) ? 'true' : 'false' }}"
                                    data-sortable="true">{{ get_label('source', 'Source') }}</th>
                                <th data-field="stage"
                                    data-visible="{{ in_array('stage', $visibleColumns) || empty($visibleColumns) ? 'true' : 'false' }}"
                                    data-sortable="true">{{ get_label('stage', 'Stage') }}</th>
                                <th data-field="assigned_to"
                                    data-visible="{{ in_array('assigned_to', $visibleColumns) || empty($visibleColumns) ? 'true' : 'false' }}"
                                    data-sortable="true">{{ get_label('assigned_to', 'Assigned To') }}</th>
                                <th data-field="created_at"
                                    data-visible="{{ in_array('created_at', $visibleColumns) || empty($visibleColumns) ? 'true' : 'false' }}"
                                    data-sortable="true">{{ get_label('created', 'Created') }}</th>
                                <th data-field="actions"
                                    data-visible="{{ in_array('actions', $visibleColumns) || empty($visibleColumns) ? 'true' : 'false' }}">
                                    {{ get_label('actions', 'Actions') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    @else
        @php $type = 'Lead Forms'; @endphp
        <x-empty-state-card :type="$type" />
    @endif
</div>

<script>
    var label_update = '{{ get_label('update', 'Update') }}';
    var label_delete = '{{ get_label('delete', 'Delete') }}';
</script>

<script src="{{ asset('assets/js/pages/lead-form.js') }}"></script>
@endsection
