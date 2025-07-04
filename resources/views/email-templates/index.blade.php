@extends('layout')

@section('title')
    <?= get_label('email_templates', 'Email Templates') ?>
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
                        {{ get_label('settings', 'settings') }}
                    </li>
                    <li class="breadcrumb-item active">
                        {{ get_label('email_templates', 'Email Templates') }}
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
                <button type="button" class="btn btn-sm btn-primary " data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="{{ get_label('create_template', 'Create Template') }}">
                    <i class='bx bx-plus'></i>
                </button>
            </a>
        </div>
    </div>

    <x-email-templates-card :templates="$templates" />
</div>

<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
    var label_duplicate = '<?= get_label('duplicate', 'Duplicate') ?>';
</script>
 <script src="{{ asset('assets/js/pages/email-templates.js') }}"></script>

@endsection
