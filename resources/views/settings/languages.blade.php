@extends('layout')
@section('title')
    <?= get_label('languages', 'Languages') ?>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-2 mt-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1">
                        <li class="breadcrumb-item">
                            <a href="{{ url('home') }}"><?= get_label('home', 'Home') ?></a>
                        </li>
                        <li class="breadcrumb-item">
                            <?= get_label('settings', 'Settings') ?>
                        </li>
                        <li class="breadcrumb-item active">
                            <?= get_label('languages', 'Languages') ?>
                        </li>
                    </ol>
                </nav>
            </div>
            <div>
                @if (app()->getLocale() == $default_language)
                    <span class="badge bg-primary" data-bs-toggle="tooltip" data-bs-placement="left"
                        data-bs-original-title="<?= get_label('current_language_is_your_primary_language', 'Current language is your primary language') ?>"><?= get_label('primary', 'Primary') ?></span>
                @else
                    <a href="javascript:void(0);"><span class="badge bg-secondary" id="set-as-default"
                            data-lang="{{ app()->getLocale() }}" data-bs-toggle="tooltip" data-bs-placement="left"
                            data-bs-original-title="<?= get_label('set_current_language_as_your_primary_language', 'Set current language as your primary language') ?>"><?= get_label('set_as_primary', 'Set as primary') ?></span></a>
                @endif
            </div>
            <form action="{{ url('settings/languages/save_labels') }}" class="form-submit-event" method="POST">
                <input type="hidden" name="redirect_url" value="{{ url('settings/languages') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="langcode" value="{{ Session::get('locale') }}">
                <div>
                    <button type="submit" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left"
                        data-bs-original-title="<?= get_label('save_language', 'Save language') ?>"><i
                            class='bx bx-save'></i></button>
                    <span data-bs-toggle="modal" data-bs-target="#create_language_modal"><a href="javascript:void(0);"
                            class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left"
                            data-bs-original-title="<?= get_label('create_language', 'Create language') ?>"><i
                                class='bx bx-plus'></i></a></span>
                    <a href="{{ url('settings/languages/manage') }}"><button type="button" class="btn btn-sm btn-primary"
                            data-bs-toggle="tooltip" data-bs-placement="right"
                            data-bs-original-title="<?= get_label('manage_languages', 'Manage languages') ?>"><i
                                class="bx bx-list-ul"></i></button></a>
                </div>
        </div>
        <?php
        $mainAdminId = getMainAdminId();
        $general_settings = get_settings('general_settings');
        ?>
        @if (auth()->user()->id == $mainAdminId && app()->getLocale() == $default_language)
            @if (!isset($general_settings['priLangAsAuth']) || $general_settings['priLangAsAuth'] == 1)
                <div class="alert alert-success">
                    {{ get_label('auth_primary_lang_enabled', 'Use the primary language chosen by the main admin for the signup, login, forgot password, and reset password interfaces is enabled.') }}
                    <a href="{{ url('settings/general') }}" class="custom-link">
                        {{ get_label('click_to_change', 'Click here to change.') }}
                    </a>
                </div>
            @else
                <div class="alert alert-danger">
                    {{ get_label('auth_primary_lang_disabled', 'Use the primary language chosen by the main admin for the signup, login, forgot password, and reset password interfaces is disabled.') }}
                    <a href="{{ url('settings/general') }}" class="custom-link">
                        {{ get_label('click_to_change', 'Click here to change.') }}
                    </a>
                </div>
            @endif
        @endif
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-2 mb-xl-0 mb-4">
                        <small class="text-light fw-semibold"><?= get_label('jump_to', 'Jump to') ?></small>
                        <div class="demo-inline-spacing mt-3">
                            <div class="list-group">
                                @foreach ($languages as $language)
                                    <a href="{{ url('settings/languages/change/' . $language->code) }}"
                                        class="list-group-item list-group-item-action {{ Session::get('locale') == $language->code ? 'active' : '' }}">{{ $language->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <small class="text-light fw-semibold"><?= get_label('labels', 'Labels') ?></small>
                        <div class="mb-3 mt-2">
                            <div class="row">
                                {!! create_label('dashboard', 'Dashboard', Session::get('locale', Session::get('locale'))) !!}
                                {!! create_label('total_projects', 'Total projects', Session::get('locale', Session::get('locale'))) !!}
                            </div>
                            <div class="row">
                                {!! create_label('total_tasks', 'Total tasks', Session::get('locale')) !!}
                                {!! create_label('total_users', 'Total users', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('total_clients', 'Total clients', Session::get('locale')) !!}
                                {!! create_label('projects', 'Projects', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('tasks', 'Tasks', Session::get('locale')) !!}
                                {!! create_label('session_expired', 'Session expired', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('log_in', 'Log in', Session::get('locale')) !!}
                                {!! create_label('search_results', 'Search results', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('no_results_found', 'No Results Found!', Session::get('locale')) !!}
                                {!! create_label('create_project', 'Create project', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create', 'Create', Session::get('locale')) !!}
                                {!! create_label('title', 'Title', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('status', 'Status', Session::get('locale')) !!}
                                {!! create_label('create_status', 'Create status', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('budget', 'Budget', Session::get('locale')) !!}
                                {!! create_label('starts_at', 'Starts at', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('ends_at', 'Ends at', Session::get('locale')) !!}
                                {!! create_label('description', 'Description', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('select_users', 'Select users', Session::get('locale')) !!}
                                {!! create_label('select_clients', 'Select clients', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'you_will_be_project_participant_automatically',
                                    'You will be project participant automatically.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('edit', 'Edit', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('grid_view', 'Grid view', Session::get('locale')) !!}
                                {!! create_label('update', 'Update', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('delete', 'Delete', Session::get('locale')) !!}
                                {!! create_label('warning', 'Warning!', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'delete_project_alert',
                                    'Are you sure you want to delete this project?',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('close', 'Close', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('yes', 'Yes', Session::get('locale')) !!}
                                {!! create_label('users', 'Users', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('view', 'View', Session::get('locale')) !!}
                                {!! create_label('add_media', 'Add Media', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('time', 'Time', Session::get('locale')) !!}
                                {!! create_label('clients', 'Clients', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('list_view', 'List view', Session::get('locale')) !!}
                                {!! create_label('draggable', 'Draggable', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_task', 'Create task', Session::get('locale')) !!}
                                {!! create_label('task', 'Task', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('project', 'Project', Session::get('locale')) !!}
                                {!! create_label('actions', 'Actions', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('delete_task_alert', 'Are you sure you want to delete this task?', Session::get('locale')) !!}
                                {!! create_label('update_project', 'Update project', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('cancel', 'Cancel', Session::get('locale')) !!}
                                {!! create_label('update_task', 'Update task', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('unread', 'Unread', Session::get('locale')) !!}
                                {!! create_label('messages', 'MESSAGES', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('contacts', 'Contacts', Session::get('locale')) !!}
                                {!! create_label('favorites', 'Favorites', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('all_messages', 'All Messages', Session::get('locale')) !!}
                                {!! create_label('search', 'Search', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('type_to_search', 'Type to search', Session::get('locale')) !!}
                                {!! create_label('connected', 'Connected', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('connecting', 'Connecting', Session::get('locale')) !!}
                                {!! create_label('no_internet_access', 'No internet access', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'please_select_a_chat_to_start_messaging',
                                    'Please select a chat to start messaging',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('user_details', 'User Details', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('delete_conversation', 'Delete Conversation', Session::get('locale')) !!}
                                {!! create_label('shared_photos', 'Shared Photos', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('you', 'You', Session::get('locale')) !!}
                                {!! create_label('save_messages_secretly', 'Save messages secretly', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('attachments', 'Attachments', Session::get('locale')) !!}
                                {!! create_label(
                                    'are_you_sure_you_want_to_delete_this',
                                    'Are you sure you want to delete this?',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('you_can_not_undo_this_action', 'You can not undo this action', Session::get('locale')) !!}
                                {!! create_label('upload_new', 'Upload New', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('dark_mode', 'Dark Mode', Session::get('locale')) !!}
                                {!! create_label('save_changes', 'Save Changes', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('save_changes', 'Save Changes', Session::get('locale')) !!}
                                {!! create_label('type_a_message', 'Type a message', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_meeting', 'Create meeting', Session::get('locale')) !!}
                                {!! create_label('meetings', 'Meetings', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'you_will_be_meeting_participant_automatically',
                                    'You will be meeting participant automatically.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('update_meeting', 'Update meeting', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_workspace', 'Create workspace', Session::get('locale')) !!}
                                {!! create_label('workspaces', 'Workspaces', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'you_will_be_workspace_participant_automatically',
                                    'You will be workspace participant automatically.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('update_workspace', 'Update workspace', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_todo', 'Create todo', Session::get('locale')) !!}
                                {!! create_label('todo_list', 'Todo list', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('priority', 'Priority', Session::get('locale')) !!}
                                {!! create_label('low', 'Low', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('medium', 'Medium', Session::get('locale')) !!}
                                {!! create_label('high', 'High', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('todo', 'Todo', Session::get('locale')) !!}
                                {!! create_label('delete_todo_warning', 'Are you sure you want to delete this todo?', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                            </div>
                            <div class="row">
                                {!! create_label('account', 'Account', Session::get('locale')) !!}
                                {!! create_label('account_settings', 'Account settings', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('profile_details', 'Profile details', Session::get('locale')) !!}
                                {!! create_label('update_profile_photo', 'Update profile photo', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('whose', 'Whose', Session::get('locale')) !!}
                                {!! create_label('first_name', 'First name', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('last_name', 'Last name', Session::get('locale')) !!}
                                {!! create_label('phone_number', 'Phone number', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('email', 'E-mail', Session::get('locale')) !!}
                                {!! create_label('role', 'Role', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('address', 'Address', Session::get('locale')) !!}
                                {!! create_label('city', 'City', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('state', 'State', Session::get('locale')) !!}
                                {!! create_label('country', 'Country', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('zip_code', 'Zip code', Session::get('locale')) !!}
                                {!! create_label('state', 'State', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('delete_account', 'Delete account', Session::get('locale')) !!}
                                {!! create_label(
                                    'delete_account_alert',
                                    'Are you sure you want to delete your account?',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'delete_account_alert_sub_text',
                                    'Once you delete your account, there is no going back. Please be certain.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('create_user', 'Create user', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('password', 'Password', Session::get('locale')) !!}
                                {!! create_label('confirm_password', 'Confirm password', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('profile_picture', 'Profile picture', Session::get('locale')) !!}
                                {!! create_label('profile', 'Profile', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('assigned', 'Assigned', Session::get('locale')) !!}
                                {!! create_label('delete_user_alert', 'Are you sure you want to delete this user?', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('client_projects', 'Client projects', Session::get('locale')) !!}
                                {!! create_label('create_client', 'Create client', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('client', 'Client', Session::get('locale')) !!}
                                {!! create_label('company', 'Company', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('phone_number', 'Phone number', Session::get('locale')) !!}
                                {!! create_label('delete_client_alert', 'Are you sure you want to delete this client?', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('draggable', 'Draggable', Session::get('locale')) !!}
                                {!! create_label('settings', 'Settings', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('smtp_host', 'SMTP host', Session::get('locale')) !!}
                                {!! create_label('smtp_port', 'SMTP port', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('email_content_type', 'Email content type', Session::get('locale')) !!}
                                {!! create_label('smtp_encryption', 'SMTP Encryption', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('general', 'General', Session::get('locale')) !!}
                                {!! create_label('company_title', 'Company title', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('full_logo', 'Full logo', Session::get('locale')) !!}
                                {!! create_label('half_logo', 'Half logo', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('favicon', 'Favicon', Session::get('locale')) !!}
                                {!! create_label('system_time_zone', 'System time zone', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('select_time_zone', 'Select time zone', Session::get('locale')) !!}
                                {!! create_label('currency_full_form', 'Currency full form', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('currency_symbol', 'Currency symbol', Session::get('locale')) !!}
                                {!! create_label('currency_code', 'Currency code', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('permission_settings', 'Permission settings', Session::get('locale')) !!}
                                {!! create_label('create_role', 'Create role', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('permissions', 'Permissions', Session::get('locale')) !!}
                                {!! create_label('no_permissions_assigned', 'No Permissions Assigned!', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('delete_role_alert', 'Are you sure you want to delete this role?', Session::get('locale')) !!}
                                {!! create_label('pusher', 'Pusher', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'important_settings_for_chat_feature_to_be_work',
                                    'Important settings for chat feature to be work',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'click_here_to_find_these_settings_on_your_pusher_account',
                                    'Click here to find these settings on your pusher account',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('pusher_app_id', 'Pusher app id', Session::get('locale')) !!}
                                {!! create_label('pusher_app_key', 'Pusher app key', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('pusher_app_secret', 'Pusher app secret', Session::get('locale')) !!}
                                {!! create_label('pusher_app_cluster', 'Pusher app cluster', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('no_meetings_found', 'No meetings found!', Session::get('locale')) !!}
                                {!! create_label(
                                    'delete_meeting_alert',
                                    'Are you sure you want to delete this meeting?',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('manage_workspaces', 'Manage workspaces', Session::get('locale')) !!}
                                {!! create_label('edit_workspace', 'Edit workspace', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('remove_me_from_workspace', 'Remove me from workspace', Session::get('locale')) !!}
                                {!! create_label('chat', 'Chat', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('todos', 'Todos', Session::get('locale')) !!}
                                {!! create_label('languages', 'Languages', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('no_projects_found', 'No projects Found!', Session::get('locale')) !!}
                                {!! create_label('no_tasks_found', 'No tasks Found!', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('no_workspace_found', 'No workspaces found!', Session::get('locale')) !!}
                                {!! create_label(
                                    'delete_workspace_alert',
                                    'Are you sure you want to delete this workspace?',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('preview', 'Preview', Session::get('locale')) !!}
                                {!! create_label('primary', 'Primary', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('secondary', 'Secondary', Session::get('locale')) !!}
                                {!! create_label('success', 'Success', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('danger', 'Danger', Session::get('locale')) !!}
                                {!! create_label('warning', 'Warning', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('info', 'Info', Session::get('locale')) !!}
                                {!! create_label('dark', 'Dark', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('labels', 'Labels', Session::get('locale')) !!}
                                {!! create_label('jump_to', 'Jump to', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('save_language', 'Save language', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'current_language_is_your_primary_language',
                                    'Current language is your primary language',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('set_as_primary', 'Set as primary', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'set_current_language_as_your_primary_language',
                                    'Set current language as your primary language',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'set_primary_lang_alert',
                                    'Are you want to set as your primary language?',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('home', 'Home', Session::get('locale')) !!}
                                {!! create_label('project_details', 'Project details', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('list', 'List', Session::get('locale')) !!}
                                {!! create_label('drag_drop_update_task_status', 'Drag and drop to update task status', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('update_role', 'Update role', Session::get('locale')) !!}
                                {!! create_label('date_format', 'Date format', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'this_date_format_will_be_used_system_wide',
                                    'This date format will be used system-wide',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('select_date_format', 'Select date format', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('select_status', 'Select status', Session::get('locale')) !!}
                                {!! create_label('sort_by', 'Sort by', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('newest', 'Newest', Session::get('locale')) !!}
                                {!! create_label('oldest', 'Oldest', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('most_recently_updated', 'Most recently updated', Session::get('locale')) !!}
                                {!! create_label('least_recently_updated', 'Least recently updated', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'important_settings_for_email_feature_to_be_work',
                                    'Important settings for email feature to be work',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'click_here_to_test_your_email_settings',
                                    'Click here to test your email settings',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('data_not_found', 'Data Not Found', Session::get('locale')) !!}
                                {!! create_label('oops!', 'Oops!', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('data_does_not_exists', 'Data does not exists', Session::get('locale')) !!}
                                {!! create_label('create_now', 'Create now', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('select_project', 'Select project', Session::get('locale')) !!}
                                {!! create_label('select', 'Select', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('not_assigned', 'Not assigned', Session::get('locale')) !!}
                                {!! create_label(
                                    'confirm_leave_workspace',
                                    'Are you sure you want leave this workspace?',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('not_workspace_found', 'No workspace(s) found', Session::get('locale')) !!}
                                {!! create_label(
                                    'must_workspace_participant',
                                    'You must be participant in atleast one workspace',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'pending_email_verification',
                                    'Pending email verification. Please check verification mail sent to you!',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('resend_verification_link', 'Resend verification link', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('id', 'ID', Session::get('locale')) !!}
                                {!! create_label('projects_grid_view', 'Projects grid view', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('tasks_list', 'Tasks list', Session::get('locale')) !!}
                                {!! create_label('task_details', 'Task details', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('update_todo', 'Update todo', Session::get('locale')) !!}
                                {!! create_label('user_profile', 'User profile', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('update_user_profile', 'Update user profile', Session::get('locale')) !!}
                                {!! create_label('update_profile', 'Update profile', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('client_profile', 'Client profile', Session::get('locale')) !!}
                                {!! create_label('update_client_profile', 'Update client profile', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('todos_not_found', 'Todos not found!', Session::get('locale')) !!}
                                {!! create_label('view_more', 'View more', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('project_statistics', 'Project statistics', Session::get('locale')) !!}
                                {!! create_label('task_statistics', 'Task statistics', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('status_wise_projects', 'Status wise projects', Session::get('locale')) !!}
                                {!! create_label('status_wise_tasks', 'Status wise tasks', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('manage_status', 'Manage status', Session::get('locale')) !!}
                                {!! create_label('ongoing', 'Ongoing', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('ended', 'Ended', Session::get('locale')) !!}
                                {!! create_label('footer_text', 'Footer text', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('view_current_full_logo', 'View current full logo', Session::get('locale')) !!}
                                {!! create_label('current_full_logo', 'Current full logo', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('view_current_half_logo', 'View current half logo', Session::get('locale')) !!}
                                {!! create_label('current_half_logo', 'Current half logo', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('view_current_favicon', 'View current favicon', Session::get('locale')) !!}
                                {!! create_label('current_favicon', 'Current favicon', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('manage_statuses', 'Manage statuses', Session::get('locale')) !!}
                                {!! create_label('statuses', 'Statuses', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('update_status', 'Update status', Session::get('locale')) !!}
                                {!! create_label(
                                    'delete_status_warning',
                                    'Are you sure you want to delete this status?',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('select_user', 'Select user', Session::get('locale')) !!}
                                {!! create_label('select_client', 'Select client', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('tags', 'Tags', Session::get('locale')) !!}
                                {!! create_label('create_tag', 'Create tag', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('manage_tags', 'Manage tags', Session::get('locale')) !!}
                                {!! create_label('update_tag', 'Update tag', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('delete_tag_warning', 'Are you sure you want to delete this tag?', Session::get('locale')) !!}
                                {!! create_label('filter_by_tags', 'Filter by tags', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('filter', 'Filter', Session::get('locale')) !!}
                                {!! create_label('type_to_search', 'Type to search', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('select_tags', 'Select tags', Session::get('locale')) !!}
                                {!! create_label('start_date_between', 'Start date between', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('end_date_between', 'End date between', Session::get('locale')) !!}
                                {!! create_label(
                                    'reload_page_to_change_chart_colors',
                                    'Reload the page to change chart colors!',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('todos_overview', 'Todos overview', Session::get('locale')) !!}
                                {!! create_label('done', 'Done', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('pending', 'Pending', Session::get('locale')) !!}
                                {!! create_label('total', 'Total', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('not_authorized', 'Not authorized', Session::get('locale')) !!}
                                {!! create_label('un_authorized_action', 'Un authorized action!', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'not_authorized_notice',
                                    'Sorry for the inconvenience but you are not authorized to perform this action',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('not_specified', 'Not specified', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('manage_projects', 'Manage projects', Session::get('locale')) !!}
                                {!! create_label('total_todos', 'Total todos', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('total_meetings', 'Total meetings', Session::get('locale')) !!}
                                {!! create_label('add_favorite', 'Click to mark as favorite', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('remove_favorite', 'Click to remove from favorite', Session::get('locale')) !!}
                                {!! create_label('favorite_projects', 'Favorite projects', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('favorite', 'Favorite', Session::get('locale')) !!}
                                {!! create_label('duplicate', 'Duplicate', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('duplicate_warning', 'Are you sure you want to duplicate?', Session::get('locale')) !!}
                                {!! create_label('leave_requests', 'Leave requests', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('leave_request', 'Leave request', Session::get('locale')) !!}
                                {!! create_label('create_leave_requet', 'Create leave request', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('to_time', 'To Time', Session::get('locale')) !!}
                                {!! create_label('partial_leave', 'Partial Leave', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('days', 'Days', Session::get('locale')) !!}
                                {!! create_label('select_roles', 'Select Roles', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('name', 'Name', Session::get('locale')) !!}
                                {!! create_label('duration', 'Duration', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('reason', 'Reason', Session::get('locale')) !!}
                                {!! create_label('action_by', 'Action by', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('approved', 'Approved', Session::get('locale')) !!}
                                {!! create_label('rejected', 'Rejected', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('update_leave_requet', 'Update leave request', Session::get('locale')) !!}
                                {!! create_label('select_leave_editors', 'Select leave editors', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('leave_editor_info', 'You are leave editor', Session::get('locale')) !!}
                                {!! create_label('from_date_between', 'From date between', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('to_date_between', 'To date between', Session::get('locale')) !!}
                                {!! create_label('contracts', 'Contracts', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_contract', 'Create contract', Session::get('locale')) !!}
                                {!! create_label('contract_types', 'Contract types', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_contract_type', 'Create contract type', Session::get('locale')) !!}
                                {!! create_label('type', 'Type', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('update_contract_type', 'Update contract type', Session::get('locale')) !!}
                                {!! create_label('created_at', 'Created at', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('signed', 'Signed', Session::get('locale')) !!}
                                {!! create_label('partially_signed', 'Partially signed', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('not_signed', 'Not signed', Session::get('locale')) !!}
                                {!! create_label('value', 'Value', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('select_contract_type', 'Select contract type', Session::get('locale')) !!}
                                {!! create_label('update_contract', 'Update contract', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('promisor_sign_status', 'Promisor sign status', Session::get('locale')) !!}
                                {!! create_label('promisee_sign_status', 'Promisee sign status', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('manage_contract_types', 'Manage contract types', Session::get('locale')) !!}
                                {!! create_label('contract', 'Contract', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('contract_id_prefix', 'Contract ID prefix', Session::get('locale')) !!}
                                {!! create_label('promiser_sign', 'Promisor sign', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('promiser_sign', 'Promisor sign', Session::get('locale')) !!}
                                {!! create_label('promisee_sign', 'Promisee sign', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('created_by', 'Created by', Session::get('locale')) !!}
                                {!! create_label('updated_at', 'Updated at', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('last_updated_at', 'Last updated at', Session::get('locale')) !!}
                                {!! create_label('create_signature', 'Create signature', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('reset', 'Reset', Session::get('locale')) !!}
                                {!! create_label('delete_signature', 'Delete signature', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('payslips', 'Payslips', Session::get('locale')) !!}
                                {!! create_label('print_contract', 'Print contract', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_payslip', 'Create payslip', Session::get('locale')) !!}
                                {!! create_label('payslip_month', 'Payslip month', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('working_days', 'Working days', Session::get('locale')) !!}
                                {!! create_label('lop_days', 'Loss of pay days', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('paid_days', 'Paid days', Session::get('locale')) !!}
                                {!! create_label('please_select', 'Please select', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('basic_salary', 'Basic salary', Session::get('locale')) !!}
                                {!! create_label('leave_deduction', 'Leave deduction', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('over_time_hours', 'Over time hours', Session::get('locale')) !!}
                                {!! create_label('over_time_rate', 'Over time rate', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('over_time_payment', 'Over time payment', Session::get('locale')) !!}
                                {!! create_label('bonus', 'Bonus', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('incentives', 'Incentives', Session::get('locale')) !!}
                                {!! create_label('payment_method', 'Payment method', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('payment_date', 'Payment date', Session::get('locale')) !!}
                                {!! create_label('paid', 'Paid', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('unpaid', 'Unpaid', Session::get('locale')) !!}
                                {!! create_label('payment_status', 'Payment status', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_payment_method', 'Create payment method', Session::get('locale')) !!}
                                {!! create_label('manage_payment_methods', 'Manage payment methods', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('payment_methods', 'Payment methods', Session::get('locale')) !!}
                                {!! create_label('allowances', 'Allowances', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('update_payment_method', 'Update payment method', Session::get('locale')) !!}
                                {!! create_label('manage_payslips', 'Manage payslips', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('manage_contracts', 'Manage contracts', Session::get('locale')) !!}
                                {!! create_label('allowance', 'Allowance', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('deduction', 'Deduction', Session::get('locale')) !!}
                                {!! create_label('amount', 'Amount', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('manage_allowances', 'Manage allowances', Session::get('locale')) !!}
                                {!! create_label('update_allowance', 'Update allowance', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_allowance', 'Create allowance', Session::get('locale')) !!}
                                {!! create_label('manage_deductions', 'Manage deductions', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_deduction', 'Create deduction', Session::get('locale')) !!}
                                {!! create_label('percentage', 'Percentage', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('deductions', 'Deductions', Session::get('locale')) !!}
                                {!! create_label('update_deduction', 'Update deduction', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('add', 'Add', Session::get('locale')) !!}
                                {!! create_label('remove', 'Remove', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('total_allowances', 'Total allowances', Session::get('locale')) !!}
                                {!! create_label('total_deductions', 'Total deductions', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('total_earning', 'Total earning', Session::get('locale')) !!}
                                {!! create_label('net_payable', 'Net payable', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('payslip_id_prefix', 'PSL (payslip ID prefix)', Session::get('locale')) !!}
                                {!! create_label('team_member', 'Team member', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('update_payslip', 'Update payslip', Session::get('locale')) !!}
                                {!! create_label('payslip', 'Payslip', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('payslip_for', 'Payslip for', Session::get('locale')) !!}
                                {!! create_label('print_payslip', 'Print payslip', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('total_allowances_and_deductions', 'Total allowances and deductions', Session::get('locale')) !!}
                                {!! create_label('no_deductions_found_payslip', 'No deductions found for this payslip.', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('no_allowances_found_payslip', 'No allowances found for this payslip.', Session::get('locale')) !!}
                                {!! create_label('total_earnings', 'Total earnings', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('select_team_member', 'Select team member', Session::get('locale')) !!}
                                {!! create_label('select_payment_status', 'Select payment status', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('select_created_by', 'Select created by', Session::get('locale')) !!}
                                {!! create_label('notes', 'Notes', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_note', 'Create note', Session::get('locale')) !!}
                                {!! create_label('upcoming_birthdays', 'Upcoming birthdays', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('upcoming_work_anniversaries', 'Upcoming work anniversaries', Session::get('locale')) !!}
                                {!! create_label('birthday_count', 'Birthday count', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('days_left', 'Days left', Session::get('locale')) !!}
                                {!! create_label('till_upcoming_days_def_30', 'Till upcoming days : default 30', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('work_anniversary_date', 'Work anniversary date', Session::get('locale')) !!}
                                {!! create_label('birth_day_date', 'Birth day date', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('select_member', 'Select member', Session::get('locale')) !!}
                                {!! create_label('update_note', 'Update note', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('today', 'Today', Session::get('locale')) !!}
                                {!! create_label('tomorrow', 'Tomorrow', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('day_after_tomorow', 'Day after tomorrow', Session::get('locale')) !!}
                                {!! create_label('on_leave', 'On leave', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('on_leave_tomorrow', 'On leave from tomorrow', Session::get('locale')) !!}
                                {!! create_label('on_leave_day_after_tomorow', 'On leave from day after tomorrow', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('dob_not_set_alert', 'You DOB is not set', Session::get('locale')) !!}
                                {!! create_label('click_here_to_set_it_now', 'Click here to set it now', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('system_updater', 'System updater', Session::get('locale')) !!}
                                {!! create_label('update_the_system', 'Update the system', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('hi', 'Hi', Session::get('locale')) !!}
                                {!! create_label('active', 'Active', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('deactive', 'Deactive', Session::get('locale')) !!}
                                {!! create_label(
                                    'status_not_active',
                                    'Your account is currently inactive. Please contact the admin for assistance.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('demo_restriction', 'This operation is not allowed in demo mode.', Session::get('locale')) !!}
                                {!! create_label('for_example', 'For example', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_description', 'Please enter description', Session::get('locale')) !!}
                                {!! create_label('please_enter_zip_code', 'Please enter ZIP code', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('time_tracker', 'Time tracker', Session::get('locale')) !!}
                                {!! create_label('start', 'Start', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('stop', 'Stop', Session::get('locale')) !!}
                                {!! create_label('pause', 'Pause', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('hours', 'Hours', Session::get('locale')) !!}
                                {!! create_label('minutes', 'Minutes', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('second', 'Second', Session::get('locale')) !!}
                                {!! create_label('message', 'Message', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('view_timesheet', 'View timesheet', Session::get('locale')) !!}
                                {!! create_label('timesheet', 'Timesheet', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('stop_timer_alert', 'Are you sure you want to stop the timer?', Session::get('locale')) !!}
                                {!! create_label('user', 'User', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('started_at', 'Started at', Session::get('locale')) !!}
                                {!! create_label('ended_at', 'Ended at', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('yet_to_start', 'Yet to start', Session::get('locale')) !!}
                                {!! create_label('select_all', 'Select all', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('users_associated_with_project', 'Users associated with project', Session::get('locale')) !!}
                                {!! create_label('admin_has_all_permissions', 'Admin has all the permissions', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('current_version', 'Current version', Session::get('locale')) !!}
                                {!! create_label('delete_selected', 'Delete selected', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'delete_selected_alert',
                                    'Are you sure you want to delete selected record(s)?',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('please_wait', 'Please wait...', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_select_records_to_delete', 'Please select records to delete.', Session::get('locale')) !!}
                                {!! create_label('something_went_wrong', 'Something went wrong.', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_correct_errors', 'Please correct errors.', Session::get('locale')) !!}
                                {!! create_label(
                                    'removed_from_favorite_successfully',
                                    'Removed from favorites successfully.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('marked_as_favorite_successfully', 'Marked as favorite successfully.', Session::get('locale')) !!}
                                {!! create_label('data_access', 'Data Access', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('all_data_access', 'All Data Access', Session::get('locale')) !!}
                                {!! create_label('allocated_data_access', 'Allocated Data Access', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('date_between', 'Date between', Session::get('locale')) !!}
                                {!! create_label('actor_id', 'Actor ID', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('actor_name', 'Actor name', Session::get('locale')) !!}
                                {!! create_label('actor_type', 'Actor type', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('type_id', 'Type ID', Session::get('locale')) !!}
                                {!! create_label('activity', 'Activity', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('type_title', 'Type title', Session::get('locale')) !!}
                                {!! create_label('select_activity', 'Select activity', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('created', 'Created', Session::get('locale')) !!}
                                {!! create_label('updated', 'Updated', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('duplicated', 'Duplicated', Session::get('locale')) !!}
                                {!! create_label('deleted', 'Deleted', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('updated_status', 'Updated status', Session::get('locale')) !!}
                                {!! create_label('unsigned', 'Unsigned', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('select_type', 'Select type', Session::get('locale')) !!}
                                {!! create_label('upload', 'Upload', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('file_name', 'File name', Session::get('locale')) !!}
                                {!! create_label('file_size', 'File size', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('download', 'Download', Session::get('locale')) !!}
                                {!! create_label('uploaded', 'Uploaded', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('project_media', 'Project media', Session::get('locale')) !!}
                                {!! create_label('task_media', 'Task media', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('media_storage', 'Media storage', Session::get('locale')) !!}
                                {!! create_label('select_storage_type', 'Select storage type', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('local_storage', 'Local storage', Session::get('locale')) !!}
                                {!! create_label('media_storage_settings', 'Media storage settings', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('parent_type_id', 'Parent type ID', Session::get('locale')) !!}
                                {!! create_label('parent_type', 'Parent type', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('parent_type_title', 'Parent type title', Session::get('locale')) !!}
                                {!! create_label('please_enter_leave_reason', 'Please enter leave reason', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_title', 'Please enter title', Session::get('locale')) !!}
                                {!! create_label('please_enter_value', 'Please enter value', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_contract_type', 'Please enter contract type', Session::get('locale')) !!}
                                {!! create_label('please_enter_amount', 'Please enter amount', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_percentage', 'Please enter percentage', Session::get('locale')) !!}
                                {!! create_label('please_enter_your_message', 'Please enter your message', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_email', 'Please enter email', Session::get('locale')) !!}
                                {!! create_label('project_activity_log', 'Project activity log', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_first_name', 'Please enter first name', Session::get('locale')) !!}
                                {!! create_label('please_enter_last_name', 'Please enter last name', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_phone_number', 'Please enter phone number', Session::get('locale')) !!}
                                {!! create_label('please_enter_password', 'Please enter password', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_company_name', 'Please enter company name', Session::get('locale')) !!}
                                {!! create_label('please_re_enter_password', 'Please re enter password', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_address', 'Please enter address', Session::get('locale')) !!}
                                {!! create_label('please_enter_city', 'Please enter city', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_state', 'Please enter state', Session::get('locale')) !!}
                                {!! create_label('please_enter_country', 'Please enter country', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_basic_salary', 'Please enter basic salary', Session::get('locale')) !!}
                                {!! create_label('please_enter_working_days', 'Please enter working days', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_lop_days', 'Please enter loss of pay days', Session::get('locale')) !!}
                                {!! create_label('please_enter_bonus', 'Please enter bonus', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_incentives', 'Please enter incentives', Session::get('locale')) !!}
                                {!! create_label('please_enter_over_time_hours', 'Please enter over time hours', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_over_time_rate', 'Please enter over time rate', Session::get('locale')) !!}
                                {!! create_label('please_enter_note_if_any', 'Please enter note if any', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_budget', 'Please enter budget', Session::get('locale')) !!}
                                {!! create_label('please_enter_role_name', 'Please enter role name', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_smtp_host', 'Enter SMTP host', Session::get('locale')) !!}
                                {!! create_label('please_enter_smtp_port', 'Enter SMTP port', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_company_title', 'Enter company title', Session::get('locale')) !!}
                                {!! create_label('please_enter_currency_full_form', 'Please enter currency full form', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_currency_symbol', 'Please enter currency symbol', Session::get('locale')) !!}
                                {!! create_label('please_enter_currency_code', 'Please enter currency code', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_aws_s3_access_key', 'Please enter AWS S3 access key', Session::get('locale')) !!}
                                {!! create_label('please_enter_aws_s3_secret_key', 'Please enter AWS S3 secret key', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_aws_s3_region', 'Please enter AWS S3 region', Session::get('locale')) !!}
                                {!! create_label('please_enter_aws_s3_bucket', 'Please enter AWS S3 bucket', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_pusher_app_id', 'Please enter pusher APP ID', Session::get('locale')) !!}
                                {!! create_label('please_enter_pusher_app_key', 'Please enter pusher APP key', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_pusher_app_secret', 'Please enter pusher APP secret', Session::get('locale')) !!}
                                {!! create_label('please_enter_pusher_app_cluster', 'Please enter pusher APP cluster', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('finance', 'Finance', Session::get('locale')) !!}
                                {!! create_label('taxes', 'Taxes', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_tax', 'Create tax', Session::get('locale')) !!}
                                {!! create_label('update_tax', 'Update tax', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('units', 'Units', Session::get('locale')) !!}
                                {!! create_label('create_unit', 'Create unit', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('update_unit', 'Update unit', Session::get('locale')) !!}
                                {!! create_label('items', 'Items', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_item', 'Create item', Session::get('locale')) !!}
                                {!! create_label('price', 'Price', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_price', 'Please enter price', Session::get('locale')) !!}
                                {!! create_label('unit', 'Unit', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('unit_id', 'Unit ID', Session::get('locale')) !!}
                                {!! create_label('update_item', 'Update item', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('estimates_invoices', 'Estimates/Invoices', Session::get('locale')) !!}
                                {!! create_label('create_estimate_invoice', 'Create estimate/invoice', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('sent', 'Sent', Session::get('locale')) !!}
                                {!! create_label('accepted', 'Accepted', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('draft', 'Draft', Session::get('locale')) !!}
                                {!! create_label('declined', 'Declined', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('expired', 'Expired', Session::get('locale')) !!}
                                {!! create_label('estimate', 'Estimate', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('invoice', 'Invoice', Session::get('locale')) !!}
                                {!! create_label('billing_details', 'Billing details', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('update_billing_details', 'Update billing details', Session::get('locale')) !!}
                                {!! create_label('please_enter_name', 'Please enter name', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('contact', 'Contact', Session::get('locale')) !!}
                                {!! create_label('please_enter_contact', 'Please enter contact', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('apply', 'Apply', Session::get('locale')) !!}
                                {!! create_label(
                                    'billing_details_updated_successfully',
                                    'Billing details updated successfully.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('note', 'Note', Session::get('locale')) !!}
                                {!! create_label('from_date', 'From date', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('to_date', 'To date', Session::get('locale')) !!}
                                {!! create_label('personal_note', 'Personal note', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'please_enter_personal_note_if_any',
                                    'Please enter personal note if any',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('item', 'Item', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('manage_items', 'Manage items', Session::get('locale')) !!}
                                {!! create_label('product_service', 'Product/Service', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('quantity', 'Quantity', Session::get('locale')) !!}
                                {!! create_label('rate', 'Rate', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('tax', 'Tax', Session::get('locale')) !!}
                                {!! create_label('sub_total', 'Sub total', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('final_total', 'Final total', Session::get('locale')) !!}
                                {!! create_label('etimate_invoice', 'Estimate/Invoice', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('estimate_id_prefix', 'Estimate ID prefix', Session::get('locale')) !!}
                                {!! create_label('invoice_id_prefix', 'Invoice ID prefix', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('update_estimate', 'Update estimate', Session::get('locale')) !!}
                                {!! create_label('estimate_details', 'Estimate details', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('invoice_details', 'Invoice details', Session::get('locale')) !!}
                                {!! create_label('estimate_summary', 'Estimate summary', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('invoice_summary', 'Invoice summary', Session::get('locale')) !!}
                                {!! create_label('select_unit', 'Select unit', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('estimate_no', 'Estimate No.', Session::get('locale')) !!}
                                {!! create_label('invoice_no', 'Invoice No.', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('storage_type_set_as_aws_s3', 'Storage type is set as AWS S3 storage', Session::get('locale')) !!}
                                {!! create_label('storage_type_set_as_local', 'Storage type is set as local storage', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('click_here_to_change', 'Click here to change', Session::get('locale')) !!}
                                {!! create_label('expenses', 'Expenses', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('expenses_types', 'Expense types', Session::get('locale')) !!}
                                {!! create_label('create_expense', 'Create expense', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('update_expense_type', 'Update expense type', Session::get('locale')) !!}
                                {!! create_label('expenses', 'Expenses', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_expense', 'Create expense', Session::get('locale')) !!}
                                {!! create_label('expense_type', 'Expense type', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('expense_date', 'Expense date', Session::get('locale')) !!}
                                {!! create_label('update_expense', 'Update expense', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('payments', 'Payments', Session::get('locale')) !!}
                                {!! create_label('create_payment', 'Create payment', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('payment_id', 'Payment ID', Session::get('locale')) !!}
                                {!! create_label('user_id', 'User ID', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('invoice_id', 'Invoice ID', Session::get('locale')) !!}
                                {!! create_label('payment_method_id', 'Payment method ID', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('payment_date_between', 'Payment date between', Session::get('locale')) !!}
                                {!! create_label('update_payment', 'Update payment', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('select_invoice', 'Select invoice', Session::get('locale')) !!}
                                {!! create_label('select_payment_method', 'Select payment method', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('fully_paid', 'Fully paid', Session::get('locale')) !!}
                                {!! create_label('partially_paid', 'Partially paid', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('estimates', 'Estimates', Session::get('locale')) !!}
                                {!! create_label('invoices', 'Invoices', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('amount_left', 'Amount left', Session::get('locale')) !!}
                                {!! create_label('not_specified', 'Not specified', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('no_payments_found_invoice', 'No payments found for this invoice.', Session::get('locale')) !!}
                                {!! create_label('no_items_found', 'No items found', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('update_invoice', 'Update invoice', Session::get('locale')) !!}
                                {!! create_label('view_estimate', 'View estimate', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('view_invoice', 'View invoice', Session::get('locale')) !!}
                                {!! create_label('currency_symbol_position', 'Currency symbol position', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('before', 'Before', Session::get('locale')) !!}
                                {!! create_label('after', 'After', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('currency_formate', 'Currency formate', Session::get('locale')) !!}
                                {!! create_label('comma_separated', 'Comma separated', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('dot_separated', 'Dot separated', Session::get('locale')) !!}
                                {!! create_label('decimal_points_in_currency', 'Decimal points in currency', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('project_milestones', 'Project milestones', Session::get('locale')) !!}
                                {!! create_label('create_milestone', 'Create milestone', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('incomplete', 'Incomplete', Session::get('locale')) !!}
                                {!! create_label('complete', 'Complete', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('cost', 'Cost', Session::get('locale')) !!}
                                {!! create_label('please_enter_cost', 'Please enter cost', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('progress', 'Progress', Session::get('locale')) !!}
                                {!! create_label('update_milestone', 'Update milestone', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('require_email_verification', 'Require email verification?', Session::get('locale')) !!}
                                {!! create_label('no', 'No', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('view_all', 'View all', Session::get('locale')) !!}
                                {!! create_label('mark_all_as_read', 'Mark all as read', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('no_unread_notifications', 'No unread notifications', Session::get('locale')) !!}
                                {!! create_label(
                                    'mark_all_notifications_as_read_alert',
                                    'Are you sure you want to mark all notifications as read?',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('notifications', 'Notifications', Session::get('locale')) !!}
                                {!! create_label('mark_as_read', 'Mark as read', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('mark_as_unread', 'Mark as unread', Session::get('locale')) !!}
                                {!! create_label(
                                    'update_notifications_status_alert',
                                    'Are you sure you want to update notification status?',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('read_at', 'Read at', Session::get('locale')) !!}
                                {!! create_label('read', 'Read', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('not_allowed_in_demo_mode', 'Not allowed in demo mode', Session::get('locale')) !!}
                                {!! create_label('notification_templates', 'Notification Templates', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('email_verification', 'Email verification', Session::get('locale')) !!}
                                {!! create_label('subject', 'Subject', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('save', 'Save', Session::get('locale')) !!}
                                {!! create_label('available_placeholders', 'Available placeholders', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('placeholder', 'Placeholder', Session::get('locale')) !!}
                                {!! create_label('action', 'Action', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('copy', 'Copy', Session::get('locale')) !!}
                                {!! create_label('action', 'Action', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_email_subject', 'Please enter email subject', Session::get('locale')) !!}
                                {!! create_label('possible_placeholders', 'Possible placeholders', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('all_available_placeholders', 'All available placeholders', Session::get('locale')) !!}
                                {!! create_label('reset_to_default', 'Reset to default', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'confirm_restore_default_template',
                                    'Are you sure you want to restore default template?',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'post_update_clear_browser_cache',
                                    'Clear your browser cache by pressing CTRL+F5 after updating the system.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('copy_to_clipboard', 'Copy to clipboard', Session::get('locale')) !!}
                                {!! create_label('account_creation', 'Account creation', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('forgot_password', 'Forgot password', Session::get('locale')) !!}
                                {!! create_label('project_assignment', 'Project assignment', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('task_assignment', 'Task assignment', Session::get('locale')) !!}
                                {!! create_label('workspace_assignment', 'Workspace assignment', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('meeting_assignment', 'Meeting assignment', Session::get('locale')) !!}
                                {!! create_label('recent_notifications', 'Recent notifications', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('activity_log', 'Activity log', Session::get('locale')) !!}
                                {!! create_label('members_on_leave', 'Members on leave', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('member', 'Member', Session::get('locale')) !!}
                                {!! create_label('filter_by_status', 'Filter by status', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('admin', 'Admin', Session::get('locale')) !!}
                                {!! create_label('manage_languages', 'Manage languages', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('code', 'Code', Session::get('locale')) !!}
                                {!! create_label('update_language', 'Update language', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('time_formate', 'Time formate', Session::get('locale')) !!}
                                {!! create_label(
                                    'this_time_format_will_be_used_system_wide',
                                    'This time format will be used system-wide',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'delete_selected_will_delete_selected_team_members_alert',
                                    'Delete selected will delete selected team members.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('messaging_and_integrations', 'Messaging & Integrations', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('messaging_integrations_settings', 'Messaging & Integrations Settings', Session::get('locale')) !!}
                                {!! create_label(
                                    'important_settings_for_SMS_feature_to_be_work',
                                    'Important settings for SMS feature to be work',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('country_code_and_phone_number', 'Country code and phone number', Session::get('locale')) !!}
                                {!! create_label('base_url', 'Base URL', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_authorization_token', 'Create authorization token', Session::get('locale')) !!}
                                {!! create_label('account_sid', 'Account SID', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('auth_token', 'Auth token', Session::get('locale')) !!}
                                {!! create_label('header', 'Header', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('body', 'Body', Session::get('locale')) !!}
                                {!! create_label('params', 'Params', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('add_header_data', 'Add header data', Session::get('locale')) !!}
                                {!! create_label('key', 'Key', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('value', 'Value', Session::get('locale')) !!}
                                {!! create_label(
                                    'add_body_data_parameters_and_values',
                                    'Add body data parameter and values',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('add_params', 'Add params', Session::get('locale')) !!}
                                {!! create_label('from_time', 'From Time', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('due', 'Due', Session::get('locale')) !!}
                                {!! create_label('all', 'All', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('start_date', 'Start date', Session::get('locale')) !!}
                                {!! create_label('end_date', 'End date', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('date_of_birth', 'Date of Birth', Session::get('locale')) !!}
                                {!! create_label('date_of_joining', 'Date of Joining', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'deactivated_user_login_restricted',
                                    'If Deactivated, the User Won\'t Be Able to Log In to Their Account',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'deactivated_client_login_restricted',
                                    'If Deactivated, the Client Won\'t Be Able to Log In to Their Account',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('month', 'Month', Session::get('locale')) !!}
                                {!! create_label('net_pay', 'Net Pay', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_expense_type', 'Create Expense Type', Session::get('locale')) !!}
                                {!! create_label('color', 'Color', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'leave_editors_create_leave_request_info',
                                    'Like Admin, Selected Users Will Be Able to Update and Create Leaves for Other Members',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'all_data_access_info',
                                    'If All Data Access Is Selected, Users Under This Roles Will Have Unrestricted Access to All Data, Irrespective of Any Specific Assignments or Restrictions',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_language', 'Create Language', Session::get('locale')) !!}
                                {!! create_label(
                                    'click_for_sms_gateway_settings_help',
                                    'Click Here for Help with SMS Gateway Settings',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('email', 'Email', Session::get('locale')) !!}
                                {!! create_label('sms', 'SMS', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'account_creation_email_info',
                                    'This template will be used for the email notification sent to notify users/clients about the successful creation of their account.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'account_creation_email_will_not_sent',
                                    'If Deactive, account creation email notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'verify_user_client_email_info',
                                    'This template will be used for the email sent for verifying new user/client creation.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'forgot_password_email_info',
                                    'This template will be used for the email notification sent to users/clients to reset their password if they have forgotten it.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'project_assignment_email_info',
                                    'This template will be used for the email notification sent to users/clients when they are assigned a project.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'project_assignment_email_will_not_sent',
                                    'If Deactive, project assignment email notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'task_assignment_email_info',
                                    'This template will be used for the email notification sent to users/clients when they are assigned a task.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'task_assignment_email_will_not_sent',
                                    'If Deactive, task assignment email notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'workspace_assignment_email_info',
                                    'This template will be used for the email notification sent to users/clients when they are added to a workspace.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'workspace_assignment_email_will_not_sent',
                                    'If Deactive, workspace assignment email notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'meeting_assignment_email_info',
                                    'This template will be used for the email notification sent to users/clients when they are added to a meeting.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'meeting_assignment_email_will_not_sent',
                                    'If Deactive, meeting assignment email notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'project_assignment_sms_info',
                                    'This template will be used for the SMS notification sent to users/clients when they are assigned a project.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'project_assignment_sms_will_not_sent',
                                    'If Deactive, project assignment SMS notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'task_assignment_sms_info',
                                    'This template will be used for the SMS notification sent to users/clients when they are assigned a task.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'task_assignment_sms_will_not_sent',
                                    'If Deactive, task assignment SMS notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'workspace_assignment_sms_info',
                                    'This template will be used for the SMS notification sent to users/clients when they are added to a workspace.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'workspace_assignment_sms_will_not_sent',
                                    'If Deactive, workspace assignment SMS notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'meeting_assignment_sms_info',
                                    'This template will be used for the SMS notification sent to users/clients when they are added to a meeting.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'meeting_assignment_sms_will_not_sent',
                                    'If Deactive, meeting assignment SMS notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('text_json', 'text/JSON', Session::get('locale')) !!}
                                {!! create_label('formdata', 'FormData', Session::get('locale')) !!}
                            </div>
                            <!-- Done actual labels till this 11-04-24 -->
                            <div class="row">
                                {!! create_label('manage', 'Manage', Session::get('locale')) !!}
                                {!! create_label('my_profile', 'My Profile', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'click_for_permission_settings_instructions',
                                    'Click Here for Permission Settings Instructions',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('sms_gateway_configuration', 'Sms Gateway Configuration', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('permission_settings_instructions', 'Permission Settings Instructions', Session::get('locale')) !!}
                                {!! create_label('create_permission', 'Create Permission', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('manage_permission', 'Manage Permission', Session::get('locale')) !!}
                                {!! create_label('edit_permission', 'Edit Permission', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('delete_permission', 'Delete Permission', Session::get('locale')) !!}
                                {!! create_label(
                                    'acc_crea_email_enabled_inf',
                                    'As Account Creation Email Status Is Active, Please Ensure Email Settings Are Configured and Operational (Not Applicable If the Client Is for Internal Purposes).',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'click_to_change_acc_crea_email_sts',
                                    'Click Here to Change Account Creation Email Status.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('project_tasks', 'Project Tasks', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('priority', 'Priority', Session::get('locale')) !!}
                                {!! create_label('priorities', 'Priorities', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_priority', 'Create priority', Session::get('locale')) !!}
                                {!! create_label('quick_view', 'Quick View', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('confirm_update_status', 'Do You Want to Update the Status?', Session::get('locale')) !!}
                                {!! create_label('manage_priorities', 'Manage Priorities', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('confirm_update_priority', 'Do You Want to Update the Priority?', Session::get('locale')) !!}
                                {!! create_label('task_accessibility', 'Task Accessibility', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('assigned_users', 'Assigned Users', Session::get('locale')) !!}
                                {!! create_label('project_users', 'Project Users', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'assigned_users_info',
                                    'You Will Need to Manually Select Task Users When Creating Tasks Under This Project.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'project_users_info',
                                    'When Creating Tasks Under This Project, the Task Users Selection Will Be Automatically Filled With Project Users.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('milestones', 'Milestones', Session::get('locale')) !!}
                                {!! create_label('media', 'Media', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('roles_can_set_status', 'Roles Can Set the Status', Session::get('locale')) !!}
                                {!! create_label(
                                    'leave_editor_access_info',
                                    'Like Admin, Selected Users Will Be Able to Update and Create Leaves for Other Members.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'roles_can_set_status_info',
                                    'Including Admin and Roles with All Data Access Permission, Users/Clients Under Selected Role(s) Will Have Permission to Set This Status.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('allowed_roles', 'Allowed Roles', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'roles_can_set_status_info_1',
                                    'Including Admin and Roles with All Data Access Permission, Roles That Can Set This Status.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('partial', 'Partial', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('full', 'Full', Session::get('locale')) !!}
                                {!! create_label('from', 'From', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('to', 'To', Session::get('locale')) !!}
                                {!! create_label('on_partial_leave', 'On Partial Leave', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('on_partial_leave_tomorrow', 'On partial leave from tomorrow', Session::get('locale')) !!}
                                {!! create_label(
                                    'on_partial_leave_day_after_tomorow',
                                    'On partial leave from day after tomorrow',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('select_statuses', 'Select Statuses', Session::get('locale')) !!}
                                {!! create_label('select_projects', 'Select Projects', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('clear_filters', 'Clear Filters', Session::get('locale')) !!}
                                {!! create_label(
                                    'leave_visible_to_info',
                                    'Disabled: Requestee, Admin, and Leave Editors, along with selected users, will be able to know when the requestee is on leave. Enabled: All team members will be able to know when the requestee is on leave.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'leave_visible_to_info_1',
                                    'Including the requestee, admin, and leave editors, users who will be able to know when the requestee is on leave (not applicable if visible to all).',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('calendar', 'Calendar', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('select_priorities', 'Select Priorities', Session::get('locale')) !!}
                                {!! create_label('creation', 'Creation', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('status_updation', 'Status Updation', Session::get('locale')) !!}
                                {!! create_label(
                                    'leave_request_creation_email_info',
                                    'This Template Will Be Used for the Email notification sent to the Admin and Leave Editors Upon the Creation of a Leave Request.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'leave_request_creation_email_will_not_sent',
                                    'If Deactive, Leave Request Creation email notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'leave_request_status_updation_email_info',
                                    'This Template Will Be Used for the Email notification sent to the Admin/Leave Editors/Requestee Upon the Status Updation of a Leave Request.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'leave_request_status_updation_email_will_not_sent',
                                    'If Deactive, Leave Request Status Updation email notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'leave_request_creation_sms_info',
                                    'This Template Will Be Used for the SMS notification sent to the Admin and Leave Editors Upon the Creation of a Leave Request.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'leave_request_creation_sms_will_not_sent',
                                    'If Deactive, Leave Request Creation SMS notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'leave_request_status_updation_sms_info',
                                    'This Template Will Be Used for the SMS notification sent to the Admin/Leave Editors/Requestee Upon the Status Updation of a Leave Request.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'leave_request_status_updation_sms_will_not_sent',
                                    'If Deactive, Leave Request Status Updation SMS notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('milestone', 'Milestone', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('optional_note', 'Optional Note', Session::get('locale')) !!}
                                {!! create_label('set_as_default_view', 'Set as Default View', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('default_view', 'Default View', Session::get('locale')) !!}
                                {!! create_label('set_default_view_alert', 'Are You Want to Set as Default View?', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('birthday', 'Birthday', Session::get('locale')) !!}
                                {!! create_label('work_anniversary', 'Work Anniversary', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('save_column_visibility', 'Save Column Visibility', Session::get('locale')) !!}
                                {!! create_label(
                                    'save_column_visibility_alert',
                                    'Are You Want to Save Column Visibility?',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('project', 'Project', Session::get('locale')) !!}
                                {!! create_label('assignment', 'Assignment', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'project_status_updation_email_info',
                                    'This Template Will Be Used for the Email notification sent to the Users/Clients Upon the Status Updation of a Project.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'project_status_updation_email_will_not_sent',
                                    'If Deactive, Project Status Updation email notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('task', 'Task', Session::get('locale')) !!}
                                {!! create_label(
                                    'project_status_updation_sms_info',
                                    'This Template Will Be Used for the SMS notification sent to the Users/Clients Upon the Status Updation of a Project.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'project_status_updation_sms_will_not_sent',
                                    'If Deactive, Project Status Updation SMS notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'task_status_updation_sms_info',
                                    'This Template Will Be Used for the SMS notification sent to the Users/Clients Upon the Status Updation of a Task.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'task_status_updation_sms_will_not_sent',
                                    'If Deactive, Task Status Updation SMS notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'no_projects_view_permission',
                                    'You don\'t have permission to view projects.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'no_tasks_view_permission',
                                    'You don\'t have permission to view tasks.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('enter_title_duplicate', 'Enter Title For Item Being Duplicated', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('update_title', 'Update Title', Session::get('locale')) !!}
                                {!! create_label(
                                    'default_email_template_info',
                                    'A Default Subject and Message Will Be Used if a Specific Email Notification Template Is Not Set',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'default_sms_template_info',
                                    'A Default Message Will Be Used if a Specific SMS Notification Template Is Not Set.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('select_users_leave_visible_to', 'Select Users Leave Visible To', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('preferences', 'Preferences', Session::get('locale')) !!}
                                {!! create_label('notification_preferences', 'Notification Preferences', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('type', 'Type', Session::get('locale')) !!}
                                {!! create_label('email', 'Email', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('sms', 'SMS', Session::get('locale')) !!}
                                {!! create_label('system', 'System', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('project_status_updation', 'Project Status Updation', Session::get('locale')) !!}
                                {!! create_label('task_status_updation', 'Task Status Updation', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('leave_request_creation', 'Leave Request Creation', Session::get('locale')) !!}
                                {!! create_label('leave_request_status_updation', 'Leave Request Status Updation', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('whatsapp', 'WhatsApp', Session::get('locale')) !!}
                                {!! create_label(
                                    'default_whatsapp_template_info',
                                    'A Default Message Will Be Used if a Specific WhatsApp Notification Template Is Not Set.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'project_assignment_whatsapp_info',
                                    'This template will be used for the whatsApp notification sent to users/clients when they are assigned a project.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'project_assignment_whatsapp_will_not_sent',
                                    'If Deactive, project assignment whatsapp notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'task_assignment_whatsapp_info',
                                    'This template will be used for the whatsapp notification sent to users/clients when they are assigned a task.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'task_assignment_whatsapp_will_not_sent',
                                    'If Deactive, task assignment whatsapp notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'task_status_updation_whatsapp_info',
                                    'This Template Will Be Used for the Whatsapp notification sent to the Users/Clients Upon the Status Updation of a Task.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'task_status_updation_whatsapp_will_not_sent',
                                    'If Deactive, Task Status Updation Whatsapp Notification won\'t be Sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'workspace_assignment_whatsapp_info',
                                    'This template will be used for the whatsapp notification sent to users/clients when they are added to a workspace.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'workspace_assignment_whatsapp_will_not_sent',
                                    'If Deactive, workspace assignment whatsapp notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'meeting_assignment_whatsapp_info',
                                    'This template will be used for the whatsapp notification sent to users/clients when they are added to a meeting.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'meeting_assignment_whatsapp_will_not_sent',
                                    'If Deactive, meeting assignment whatsapp notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'leave_request_creation_whatsapp_info',
                                    'This Template Will Be Used for the Whatsapp notification sent to the Admin and Leave Editors Upon the Creation of a Leave Request.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'leave_request_creation_whatsapp_will_not_sent',
                                    'If Deactive, Leave Request Creation Whatsapp Notification Won\'t be Sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'leave_request_status_updation_whatsapp_info',
                                    'This Template Will Be Used for the Whatsapp notification sent to the Admin/Leave Editors/Requestee Upon the Status Updation of a Leave Request.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'leave_request_status_updation_whatsapp_will_not_sent',
                                    'If Deactive, Leave Request Status Updation Whatsapp Notification Won\'t be Sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('whatsapp', 'WhatsApp', Session::get('locale')) !!}
                                {!! create_label(
                                    'important_settings_for_whatsapp_notification_feature_to_be_work',
                                    'Important settings for WhatsApp notification feature to be work.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('from_no', 'From Number', Session::get('locale')) !!}
                                {!! create_label('click_for_help', 'Click here for help.', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('whatsapp_configuration', 'WhatsApp Configuration', Session::get('locale')) !!}
                                {!! create_label('twilio_account_sid', 'Twilio Account SID', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('twilio_auth_token', 'Twilio Auth Token', Session::get('locale')) !!}
                                {!! create_label('discussion', 'Discussion', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('chat', 'Chat', Session::get('locale')) !!}
                                {!! create_label('project_discussion', 'Project Discussion', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('task_discussion', 'Task Discussion', Session::get('locale')) !!}
                                {!! create_label('system', 'System', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'default_system_template_info',
                                    'A Default Title and Message Will Be Used if a Specific System Notification Template Is Not Set.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'project_assignment_system_info',
                                    'This template will be used for the system notification sent to users/clients when they are assigned a project.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'project_assignment_system_will_not_sent',
                                    'If Deactive, project assignment system notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'project_status_updation_system_info',
                                    'This Template Will Be Used for the System notification sent to the Users/Clients Upon the Status Updation of a Project.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'project_status_updation_system_will_not_sent',
                                    'If Deactive, Project Status Updation System Notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'task_assignment_system_info',
                                    'This template will be used for the system notification sent to users/clients when they are assigned a task.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'task_assignment_system_will_not_sent',
                                    'If Deactive, task assignment system notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'task_status_updation_system_info',
                                    'This Template Will Be Used for the System notification sent to the Users/Clients Upon the Status Updation of a Task.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'task_status_updation_system_will_not_sent',
                                    'If Deactive, Task Status Updation system notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'workspace_assignment_system_info',
                                    'This template will be used for the system notification sent to users/clients when they are added to a workspace.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'workspace_assignment_system_will_not_sent',
                                    'If Deactive, workspace assignment system notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'meeting_assignment_system_info',
                                    'This template will be used for the system notification sent to users/clients when they are added to a meeting.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'meeting_assignment_system_will_not_sent',
                                    'If Deactive, meeting assignment system notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'leave_request_creation_system_info',
                                    'This Template Will Be Used for the System notification sent to the Admin and Leave Editors Upon the Creation of a Leave Request.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'leave_request_creation_system_will_not_sent',
                                    'If Deactive, Leave Request Creation system notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'leave_request_status_updation_system_info',
                                    'This Template Will Be Used for the System notification sent to the Admin/Leave Editors/Requestee Upon the Status Updation of a Leave Request.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'leave_request_status_updation_system_will_not_sent',
                                    'If Deactive, Leave Request Status Updation System Notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('system', 'System', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_title', 'Please enter title', Session::get('locale')) !!}
                                {!! create_label('system', 'System', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('total_allowance', 'Total allowance', Session::get('locale')) !!}
                                {!! create_label('logout', 'Logout', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('showing', 'showing', Session::get('locale')) !!}
                                {!! create_label('to_for_pagination', 'to', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('of', 'of', Session::get('locale')) !!}
                                {!! create_label('rows', 'rows', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('rows_per_page', 'rows per page', Session::get('locale')) !!}
                                {!! create_label('or', 'or', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('drag_and_drop_files_here', 'Drag & Drop Files Here', Session::get('locale')) !!}
                                {!! create_label(
                                    'drag_and_drop_update_zip_file_here',
                                    'Drag & Drop Update from vX.X.X to vX.X.X.zip file Here',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'only_one_file_can_be_uploaded_at_a_time',
                                    'Only 1 file can be uploaded at a time',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('team_member_on_leave_alert', 'Team Member on Leave Alert', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'team_member_on_leave_alert_email_info',
                                    'This template will be used for the email notification sent to team members upon approval of a leave request, informing them about the absence of the requestee.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'team_member_on_leave_alert_email_will_not_sent',
                                    'If Deactive, Team Member on Leave Alert email notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'team_member_on_leave_alert_system_info',
                                    'This template will be used for the system notification sent to team members upon approval of a leave request, informing them about the absence of the requestee.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'team_member_on_leave_alert_system_will_not_sent',
                                    'If Deactive, Team Member on Leave Alert System Notification Won\'t be Sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'team_member_on_leave_alert_sms_info',
                                    'This template will be used for the SMS notification sent to team members upon approval of a leave request, informing them about the absence of the requestee.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'team_member_on_leave_alert_sms_will_not_sent',
                                    'If Deactive, Team Member on Leave Alert SMS Notification Won\'t be Sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'team_member_on_leave_alert_whatsapp_info',
                                    'This template will be used for the WhatsApp notification sent to team members upon approval of a leave request, informing them about the absence of the requestee.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'team_member_on_leave_alert_whatsapp_will_not_sent',
                                    'If Deactive, Team Member on Leave Alert WhatsApp Notification Won\'t be Sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('internal_client', 'Is this a client for internal purpose only?', Session::get('locale')) !!}
                                {!! create_label('internal_purpose', 'Internal Purpose', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('all', 'All', Session::get('locale')) !!}
                                {!! create_label('normal', 'Normal', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'user_acc_crea_email_enabled_inf',
                                    'As Account Creation Email Status Is Active, Please Ensure Email Settings Are Configured and Operational.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'client_require_email_verification_info',
                                    'If Yes is selected, client will receive a verification link via email. Please ensure that email settings are configured and operational.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'user_require_email_verification_info',
                                    'If Yes is selected, user will receive a verification link via email. Please ensure that email settings are configured and operational.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'internal_client_info',
                                    'Select this option if you want to create a client for internal use only, without granting account access to the client.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'account_internal_purpose',
                                    'Your account is recognized for internal purposes, Please contact the admin for assistance.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('create_account', 'Create a new account', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('submit', 'Submit', Session::get('locale')) !!}
                                {!! create_label('as_client', 'As a Client', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('as_team_member', 'As a Team member', Session::get('locale')) !!}
                                {!! create_label(
                                    'email_not_configured_info',
                                    'Email settings are not configured, which is required for the email verification process. Please contact the admin for assistance.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('primary_workspace', 'Primary Workspace', Session::get('locale')) !!}
                                {!! create_label(
                                    'primary_workspace_not_set_info',
                                    'Signup is enabled, but primary workspace is not set, which is required for signup.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('click_to_set_now', 'Click here to set it now', Session::get('locale')) !!}
                                {!! create_label(
                                    'primary_workspace_not_set_info_signup',
                                    'Primary workspace is not set, which is required for signup. Please contact the admin for assistance.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('enable_disable_signup', 'Enable/Disable Signup', Session::get('locale')) !!}
                                {!! create_label(
                                    'enable_disable_signup_info',
                                    'If disabled, team member and client will not be able to create an account by themselves.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('click_to_disable_signup', 'click here to disable signup.', Session::get('locale')) !!}
                                {!! create_label('action_not_allowed', 'This action is not allowed.', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('login', 'Login', Session::get('locale')) !!}
                                {!! create_label('welcome_to', 'Welcome to', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('sign_into_your_account', 'Sign into your account', Session::get('locale')) !!}
                                {!! create_label('dont_have_account', 'Don\'t have an account?', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('sign_up', 'Sign Up', Session::get('locale')) !!}
                                {!! create_label(
                                    'forgot_password_info',
                                    'Enter your email and we\'ll send you password reset link',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('back_to_login', 'Back to login', Session::get('locale')) !!}
                                {!! create_label('reset_password', 'Reset Password', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'reset_password_info',
                                    'Enter details and hit submit to reset your password',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('please_enter_new_password', 'Please enter new password', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('new_password', 'New password', Session::get('locale')) !!}
                                {!! create_label('confirm_new_password', 'Confirm new password', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'please_enter_confirm_new_password',
                                    'Please enter confirm new password',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('back_to_forgot_password', 'Back to forgot password', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('toast_message_position', 'Toast message position', Session::get('locale')) !!}
                                {!! create_label('toast_message_time_out', 'Toast message time out', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('bottom_right', 'Bottom Right', Session::get('locale')) !!}
                                {!! create_label('bottom_left', 'Bottom Left', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('top_left', 'Top Left', Session::get('locale')) !!}
                                {!! create_label('top_right', 'Top Right', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('top_full_width', 'Top Full Width', Session::get('locale')) !!}
                                {!! create_label('bottom_full_width', 'Bottom Full Width', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('top_center', 'Top Center', Session::get('locale')) !!}
                                {!! create_label('bottom_center', 'Bottom Center', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'toast_position_info',
                                    'Choose where on the screen toast messages will appear.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'toast_time_out_info',
                                    'Set the duration (in seconds) for how long toast messages will be displayed. The default is 5 seconds.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('preview_toast', 'Preview Toast', Session::get('locale')) !!}
                                {!! create_label('default', 'Default', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('notification_users', 'Noti. users', Session::get('locale')) !!}
                                {!! create_label('notification_clients', 'Noti. clients', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('visible_to', 'Visible To', Session::get('locale')) !!}
                                {!! create_label('send_test_message', 'Send Test Message', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'test_sms_notification_settings_info',
                                    'This is where you can test your SMS notification settings. Before testing, please update the settings if they haven\'t been updated already.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('recipient_phone_number', 'Recipient Phone Number', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'notification_test_recipient_country_code_info',
                                    'Enter if required for the platform you are using.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('test_sms_notification_settings', 'Test SMS Notification Settings', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('response', 'Response', Session::get('locale')) !!}
                                {!! create_label('sending', 'Sending...', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('recipient_country_code', 'Recipient Country Code', Session::get('locale')) !!}
                                {!! create_label('test_whatsapp_settings', 'Test WhatsApp Settings', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'test_whatsapp_notification_settings',
                                    'Test WhatsApp Notification Settings',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'test_whatsapp_notification_settings_info',
                                    'This is where you can test your WhatsApp notification settings. Before testing, please update the settings if they haven\'t been updated already.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('recipient_whatsapp_number', 'Recipient WhatsApp Number', Session::get('locale')) !!}
                                {!! create_label(
                                    'allowed_max_upload_size_in_mb_default_512',
                                    'Allowed Max Upload Size (MB) - Default: 512',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'allowed_max_upload_size_info',
                                    'Also, set the `upload_max_filesize` and `post_max_size` PHP configurations on your server accordingly to ensure the maximum upload size works as expected.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('allowed_max_upload_size', 'Allowed max upload size', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('updated_priority', 'Updated priority', Session::get('locale')) !!}
                                {!! create_label('max_attempts', 'Max Attempts', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'max_attempts_info',
                                    'Fill in if you want to set a limit; otherwise, leave it blank',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'max_attempts_info_1',
                                    'The maximum number of login attempts allowed before the account is locked. The default is 5 attempts.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('lock_time', 'Lock Time (minutes)', Session::get('locale')) !!}
                                {!! create_label('lock_time_info', 'This will not apply if Max Attempts is left blank', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'lock_time_info_1',
                                    'The duration in minutes for which the account will be locked after exceeding the maximum login attempts.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('security', 'Security', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('security_settings', 'Security settings', Session::get('locale')) !!}
                                {!! create_label('account_not_found', 'Account not found!', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'currency_restriction',
                                    'Only digits, commas as thousand separators, and a single decimal point are allowed.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('currency_restriction_1', 'Only one decimal point is allowed.', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'currency_restriction_2',
                                    'Only digits and a single decimal point are allowed.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('filter_by_statuses', 'Filter by statuses', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('select_types', 'Select Types', Session::get('locale')) !!}
                                {!! create_label('starts_at_between', 'Starts at between', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('ends_at_between', 'Ends at between', Session::get('locale')) !!}
                                {!! create_label('select_creators', 'Select Creators', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('select_invoices', 'Select Invoices', Session::get('locale')) !!}
                                {!! create_label('select_payment_methods', 'Select payment methods', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('select_units', 'Select Units', Session::get('locale')) !!}
                                {!! create_label('select_actions_by', 'Select Actions By', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'type_to_search_users_leave_visible_to',
                                    'Type To Search Users Leave Visible To',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('select_activities', 'Select Activities', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('actioned_by_id', 'Actioned By ID', Session::get('locale')) !!}
                                {!! create_label('actioned_by', 'Actioned By', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('actioned_by_type', 'Actioned By Type', Session::get('locale')) !!}
                                {!! create_label('select_actioned_by_users', 'Select Actioned By Users', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('select_actioned_by_clients', 'Select Actioned By Clients', Session::get('locale')) !!}
                                {!! create_label('as_user', 'As a User', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('select_user_creators', 'Select User Creators', Session::get('locale')) !!}
                                {!! create_label('select_client_creators', 'Select Client Creators', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('user_account', 'User Account', Session::get('locale')) !!}
                                {!! create_label('client_account', 'Client Account', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('select_members', 'Select Members', Session::get('locale')) !!}
                                {!! create_label(
                                    'primary_workspace_info',
                                    'This workspace will be assigned upon new signup.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'default_workspace_info',
                                    'On login, you will be automatically switched to this workspace by default.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('is_default', 'Is default?', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'default_push_template_info',
                                    'A Default Title and Message Will Be Used if a Specific Push Notification Template Is Not Set.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'project_assignment_push_info',
                                    'This template will be used for the push notification sent to users/clients when they are assigned a project.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'project_assignment_push_will_not_sent',
                                    'If Deactive, project assignment push notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'project_status_updation_push_info',
                                    'This Template Will Be Used for the push notification sent to the Users/Clients Upon the Status Updation of a Project.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'project_status_updation_push_will_not_sent',
                                    'If Deactive, Project Status Updation push Notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'task_assignment_push_info',
                                    'This template will be used for the push notification sent to users/clients when they are assigned a task.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'task_assignment_push_will_not_sent',
                                    'If Deactive, task assignment push notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'task_status_updation_push_info',
                                    'This Template Will Be Used for the push notification sent to the Users/Clients Upon the Status Updation of a Task.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'task_status_updation_push_will_not_sent',
                                    'If Deactive, Task Status Updation push notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'workspace_assignment_push_info',
                                    'This template will be used for the push notification sent to users/clients when they are added to a workspace.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'workspace_assignment_push_will_not_sent',
                                    'If Deactive, workspace assignment push notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'meeting_assignment_push_info',
                                    'This template will be used for the push notification sent to users/clients when they are added to a meeting.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'meeting_assignment_push_will_not_sent',
                                    'If Deactive, meeting assignment push notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'leave_request_creation_push_info',
                                    'This Template Will Be Used for the push notification sent to the Admin and Leave Editors Upon the Creation of a Leave Request.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'leave_request_creation_push_will_not_sent',
                                    'If Deactive, Leave Request Creation push notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'leave_request_status_updation_push_info',
                                    'This Template Will Be Used for the push notification sent to the Admin/Leave Editors/Requestee Upon the Status Updation of a Leave Request.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'leave_request_status_updation_push_will_not_sent',
                                    'If Deactive, Leave Request Status Updation push Notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'team_member_on_leave_alert_push_info',
                                    'This template will be used for the push notification sent to team members upon approval of a leave request, informing them about the absence of the requestee.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'team_member_on_leave_alert_push_will_not_sent',
                                    'If Deactive, Team Member on Leave Alert push Notification Won\'t be Sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('please_enter_message', 'Please Enter Message', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('push_in_app', 'Push (In APP)', Session::get('locale')) !!}
                                {!! create_label(
                                    'you_will_be_task_participant_automatically',
                                    'You will be task participant automatically.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_type_at_least_1_character', 'Please type at least 1 character', Session::get('locale')) !!}
                                {!! create_label('searching', 'Searching...', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'use_primary_lang_for_auth_interfaces',
                                    'Use the primary language chosen by the main admin for the signup, login, forgot password, and reset password interfaces.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'auth_primary_lang_enabled',
                                    'Use the primary language chosen by the main admin for the signup, login, forgot password, and reset password interfaces is enabled.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'auth_primary_lang_disabled',
                                    'Use the primary language chosen by the main admin for the signup, login, forgot password, and reset password interfaces is disabled.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('click_to_change', 'Click here to change.', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'verification_instructions',
                                    'It looks like you\'re logged in as a different user. To verify the email associated with this link, please either:',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'log_out_and_try_again',
                                    'Log out of your current account and try the verification link again, or',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'open_in_new_tab',
                                    'Open the verification link in a new tab or an incognito window where no user is logged in.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('unverified_email', 'Unverified Email', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('verified', 'Verified', Session::get('locale')) !!}
                                {!! create_label('not_verified', 'Not Verified', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('select_ev_statuses', 'Select Email Verification Statuses', Session::get('locale')) !!}
                                {!! create_label('not_applicable', 'Not Applicable', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'chat_settings_not_set',
                                    'Important settings for the chat feature are not set.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('click_to_configure', 'Click here to configure them now.', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('max_files_allowed', 'Max Files Allowed', Session::get('locale')) !!}
                                {!! create_label('allowed_file_types', 'Allowed File Types', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'max_files_allowed_info',
                                    'Set the maximum number of files that can be uploaded at a time. Also, set the `max_file_uploads` PHP configurations on your server accordingly to ensure the Max Files Allowed works as expected.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'allowed_file_types_info',
                                    'Specify the file types allowed for upload, separated by commas. Default: .pdf, .doc, .docx, .png, .jpg, .xls, .xlsx, .zip, .rar, .txt.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('no_files_chosen', 'No file(s) chosen.', Session::get('locale')) !!}
                                {!! create_label('max_files_count_allowed', 'You can only upload :count file(s).', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('file_type_not_allowed', 'File type not allowed', Session::get('locale')) !!}
                                {!! create_label('upcoming_birthdays_section', 'Upcoming birthdays section', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'upcoming_work_anniversaries_section',
                                    'Upcoming work anniversaries section',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('members_on_leave_section', 'Members on leave section', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'enable_upcoming_birthdays_section',
                                    'Enable or disable showing the upcoming birthdays section on the dashboard page.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'enable_upcoming_work_anniversaries_section',
                                    'Enable or disable showing the upcoming work anniversaries section on the dashboard page.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'enable_mol_section',
                                    'Enable or disable showing the members on leave section on the dashboard page.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('primary_language_auth', 'Primary Language for Auth', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('slack', 'Slack', Session::get('locale')) !!}
                                {!! create_label('slack_bot_configuration', 'Slack Bot Configuration', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('whatsapp_access_token', 'WhatsApp Access Token', Session::get('locale')) !!}
                                {!! create_label('please_enter', 'Please Enter', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('whatsapp_phone_number_id', 'WhatsApp Phone Number ID', Session::get('locale')) !!}
                                {!! create_label(
                                    'important_settings_for_slack_notification_feature_to_be_work',
                                    'Important settings for Slack notification feature to be work.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('slack_webhook_url', 'Slack Webhook URL', Session::get('locale')) !!}
                                {!! create_label(
                                    'default_slack_template_info',
                                    'A Default Title and Message Will Be Used if a Specific Slack Notification Template Is Not Set.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'project_assignment_slack_info',
                                    'This template will be used for the slack notification sent to users/clients when they are assigned a project.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'project_assignment_slack_will_not_sent',
                                    'If Deactive, project assignment slack notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'project_status_updation_slack_info',
                                    'This Template Will Be Used for the slack notification sent to the Users/Clients Upon the Status Updation of a Project.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'project_status_updation_slack_will_not_sent',
                                    'If Deactive, Project Status Updation slack Notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'task_assignment_slack_info',
                                    'This template will be used for the slack notification sent to users/clients when they are assigned a task.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'task_assignment_slack_will_not_sent',
                                    'If Deactive, task assignment slack notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'task_status_updation_slack_info',
                                    'This Template Will Be Used for the slack notification sent to the Users/Clients Upon the Status Updation of a Task.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'task_status_updation_slack_will_not_sent',
                                    'If Deactive, Task Status Updation slack notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'workspace_assignment_slack_info',
                                    'This template will be used for the slack notification sent to users/clients when they are added to a workspace.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'workspace_assignment_slack_will_not_sent',
                                    'If Deactive, workspace assignment slack notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'meeting_assignment_slack_info',
                                    'This template will be used for the slack notification sent to users/clients when they are added to a meeting.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'meeting_assignment_slack_will_not_sent',
                                    'If Deactive, meeting assignment slack notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'leave_request_creation_slack_info',
                                    'This Template Will Be Used for the slack notification sent to the Admin and Leave Editors Upon the Creation of a Leave Request.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'leave_request_creation_slack_will_not_sent',
                                    'If Deactive, Leave Request Creation slack notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'leave_request_status_updation_slack_info',
                                    'This Template Will Be Used for the slack notification sent to the Admin/Leave Editors/Requestee Upon the Status Updation of a Leave Request.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'leave_request_status_updation_slack_will_not_sent',
                                    'If Deactive, Leave Request Status Updation slack Notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'team_member_on_leave_alert_slack_info',
                                    'This template will be used for the slack notification sent to team members upon approval of a leave request, informing them about the absence of the requestee.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'team_member_on_leave_alert_slack_will_not_sent',
                                    'If Deactive, Team Member on Leave Alert slack Notification Won\'t be Sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('kanban_view', 'Kanban View', Session::get('locale')) !!}
                                {!! create_label('select_sort_by', 'Select Sort By', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('grid', 'Grid', Session::get('locale')) !!}
                                {!! create_label('kanban', 'Kanban', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('project_mind_map_view', 'Project Map View', Session::get('locale')) !!}
                                {!! create_label('mind_map_view', 'Mind Map View', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('export_mindmap', 'Export Mind Map', Session::get('locale')) !!}
                                {!! create_label('mm_export_success', 'Mind map exported successfully!', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('mm_export_failed', 'Failed to export mind map. Please try again.', Session::get('locale')) !!}
                                {!! create_label(
                                    'mind_map_collapse_info',
                                    'Click the yellow dots to expand or collapse sections of the mind map.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('mind_map', 'Mind Map', Session::get('locale')) !!}
                                {!! create_label('calendar_view', 'Calendar View', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('confirm_update_task_dates', 'Are You Want to Update the Task Dates?', Session::get('locale')) !!}
                                {!! create_label(
                                    'confirm_update_task_end_date',
                                    'Are You Want to Update the Task End Date?',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('draggable_view', 'Draggable View', Session::get('locale')) !!}
                                {!! create_label('clear_system_cache', 'Clear System Cache', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('customize_menu_order', 'Customize Menu Order', Session::get('locale')) !!}
                                {!! create_label(
                                    'confirm_reset_default_menu',
                                    'Are you sure you want to reset the menu order to the default?',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('search_menu', 'Search Menu...', Session::get('locale')) !!}
                                {!! create_label(
                                    'permission_denied',
                                    'You do not have permission to access this resource.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('slack_bot_token', 'Slack bot token', Session::get('locale')) !!}
                                {!! create_label('privacy_policy', 'Privacy policy', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('notification_types', 'Noti. Types', Session::get('locale')) !!}
                                {!! create_label('select_modules', 'Select Modules', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('gantt_chart_view', 'Gantt Chart View', Session::get('locale')) !!}
                                {!! create_label('select_view_mode', 'Select View Mode', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('weeks', 'Weeks', Session::get('locale')) !!}
                                {!! create_label('months', 'Months', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('no_projects_available', 'No Projects Available', Session::get('locale')) !!}
                                {!! create_label('confirm_update_dates', 'Do you want to update the date(s)?', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('change_date_not_allowed', 'Change date is not allowed for this.', Session::get('locale')) !!}
                                {!! create_label(
                                    'project_gantt_info',
                                    'Double-click a project or task to view the detail page.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('reports', 'Reports', Session::get('locale')) !!}
                                {!! create_label('income_vs_expense_report', 'Income vs Expense Report', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('total_income', 'Total Income', Session::get('locale')) !!}
                                {!! create_label('loading', 'Loading...', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('total_expense', 'Total Expense', Session::get('locale')) !!}
                                {!! create_label('profit_or_loss', 'Profit or Loss', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('export', 'Export', Session::get('locale')) !!}
                                {!! create_label('date_range', 'Date Range', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('no_data_available', 'No Data Available', Session::get('locale')) !!}
                                {!! create_label('income_vs_expense', 'Income vs Expense', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('export_income_expense_report', 'Export Income vs Expense Report', Session::get('locale')) !!}
                                {!! create_label('expense_details', 'Expense Details', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('date', 'Date', Session::get('locale')) !!}
                                {!! create_label('addi_info', 'Additional Information', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'report_footer',
                                    'This report was generated automatically. For any questions or concerns, please contact admin for support.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('generated_by', 'Generated By', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('income', 'Income', Session::get('locale')) !!}
                                {!! create_label('expense', 'Expense', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('amounts_in', 'Amounts In', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('discussions', 'Discussions', Session::get('locale')) !!}
                                {!! create_label('add_comment', 'Add Comment', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('jump_to_comment', 'Jump to Comment', Session::get('locale')) !!}
                                {!! create_label('edited', 'Edited', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('reply', 'Reply', Session::get('locale')) !!}
                                {!! create_label('no_comments', 'No Comments', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('load_more', 'Load More', Session::get('locale')) !!}
                                {!! create_label('hide', 'Hide', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('post_reply', 'Post Reply', Session::get('locale')) !!}
                                {!! create_label('attachment', 'Attachment', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('edit_comment', 'Edit Comment', Session::get('locale')) !!}
                                {!! create_label('delete_comment', 'Delete Comment', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'confirm_delete_comment',
                                    'Are you sure you want to delete this comment?',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('hide', 'Hide', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('invalid_workspace', 'The workspace you selected is invalid', Session::get('locale')) !!}
                                {!! create_label('please_enter_comment', 'Please Enter Comment', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('jump_to_comment', 'Jump to Comment', Session::get('locale')) !!}
                                {!! create_label('replies', 'Replies', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('preview_not_available', 'Preview not available', Session::get('locale')) !!}
                                {!! create_label('please_enter_reply', 'Please Enter Reply', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('recipient_email', 'Recipient Email', Session::get('locale')) !!}
                                {!! create_label('test_slack_notification_settings', 'Test Slack Notification Settings', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'test_slack_notification_settings_info',
                                    'This is where you can test your Slack notification settings. Before testing, please update the settings if they haven\'t been updated already.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('terms_privacy_about', 'Terms, Privacy & About', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('terms_conditions', 'Terms and Conditions', Session::get('locale')) !!}
                                {!! create_label('about_us', 'About Us', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'no_role_assigned',
                                    'You do not have any assigned role. Please contact the admin for assistance.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('comment', 'Comment', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('comment', 'Comment', Session::get('locale')) !!}
                                {!! create_label('optional_comment_placeholder', 'Please Enter Comment, if Any', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('leaves', 'Leaves', Session::get('locale')) !!}
                                {!! create_label('leaves_report', 'Leaves Report', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('leave_details', 'Leaves Details', Session::get('locale')) !!}
                                {!! create_label('export_leaves_report', 'Export Leaves Report', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('projects_report', 'Projects Report', Session::get('locale')) !!}
                                {!! create_label('total_team_members', 'Total Team Members', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('average_overdue_days_per_project', 'Avg. Overdue Days/Project', Session::get('locale')) !!}
                                {!! create_label('overdue_projects', 'Overdue Projects', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('total_overdue_days', 'Total Overdue Days', Session::get('locale')) !!}
                                {!! create_label('team', 'Team', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('export_projects_report', 'Export Projects Report', Session::get('locale')) !!}
                                {!! create_label('dates', 'Dates', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('total_days', 'Total Days', Session::get('locale')) !!}
                                {!! create_label('days_elapsed', 'Days Elapsed', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('days_remaining', 'Days Remaining', Session::get('locale')) !!}
                                {!! create_label('due', 'Due', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('overdue', 'Overdue', Session::get('locale')) !!}
                                {!! create_label('overdue_days', 'Overdue Days', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('team_members', 'Team Members', Session::get('locale')) !!}
                                {!! create_label('total_team_members', 'Total Team Members', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('tasks_report', 'Tasks Report', Session::get('locale')) !!}
                                {!! create_label('overdue_tasks', 'Overdue Tasks', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('average_task_completion_days', 'Avg. Task Completion Days', Session::get('locale')) !!}
                                {!! create_label('urgent_tasks', 'Urgent Tasks', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('export_tasks_report', 'Export Tasks Report', Session::get('locale')) !!}
                                {!! create_label(
                                    'urgent_tasks_info',
                                    'Tasks with a \'Danger\' priority color that have passed their due date are considered urgent.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('tasks_details', 'Tasks Details', Session::get('locale')) !!}
                                {!! create_label('members', 'Members', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('estimates_invoices_report', 'Estimates/Invoices Report', Session::get('locale')) !!}
                                {!! create_label('total_amount', 'Total Amount', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('total_tax', 'Total Tax', Session::get('locale')) !!}
                                {!! create_label('average_value', 'Average Value', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('export_report', 'Export Report', Session::get('locale')) !!}
                                {!! create_label('timestamps', 'Timestamps', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('estimates_report', 'Estimates Report', Session::get('locale')) !!}
                                {!! create_label('invoices_report', 'Invoices Report', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('details', 'Details', Session::get('locale')) !!}
                                {!! create_label('estimates_and_invoices_report', 'Estimates and Invoices Report', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('estimates_and_invoices', 'Estimates and Invoices', Session::get('locale')) !!}
                                {!! create_label('make_call', 'Make Call', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('send_mail', 'Send Mail', Session::get('locale')) !!}
                                {!! create_label('company_info', 'Company Information', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_company_email', 'Please Enter Company Email', Session::get('locale')) !!}
                                {!! create_label(
                                    'please_enter_company_phone_number',
                                    'Please Enter Company Phone Number',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('website', 'Website', Session::get('locale')) !!}
                                {!! create_label('vat_number', 'VAT Number', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_company_address', 'Please Enter Company Address', Session::get('locale')) !!}
                                {!! create_label('please_enter_company_city', 'Please Enter Company City', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_company_state', 'Please Enter Company State', Session::get('locale')) !!}
                                {!! create_label('please_enter_company_zip_code', 'Please Enter Company Zip Code', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_company_website', 'Please Enter Company Website', Session::get('locale')) !!}
                                {!! create_label('please_enter_company_vat_number', 'Please Enter Company VAT Number', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_company_country', 'Please Enter Company Country', Session::get('locale')) !!}
                                {!! create_label('view_pdf', 'View PDF', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'company_info_info',
                                    'This information will be displayed on estimates and invoices.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('err_try_again', 'An error occurred. Please try again.', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('click_unpin', 'Click to Unpin', Session::get('locale')) !!}
                                {!! create_label('click_pin', 'Click to Pin', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('pinned_successfully', 'Pinned Successfully.', Session::get('locale')) !!}
                                {!! create_label('unpinned_successfully', 'Unpinned Successfully.', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('due_projects_info', 'Projects have deadline today.', Session::get('locale')) !!}
                                {!! create_label('due_tasks_info', 'Tasks have deadline today.', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('day', 'Day', Session::get('locale')) !!}
                                {!! create_label('hour', 'Hour', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('manage_tasks', 'Manage Tasks', Session::get('locale')) !!}
                                {!! create_label('favorite_tasks', 'Favorite Tasks', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('unknown_user', 'Unknown User', Session::get('locale')) !!}
                                {!! create_label('client_can_discuss', 'Client Can Discuss', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'client_can_discuss_info',
                                    'Allows the client to participate in project discussions.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'client_can_discuss_info_task',
                                    'Allows the client to participate in task discussions.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('tasks_if_projects', 'Tasks (if Projects selected)', Session::get('locale')) !!}
                                {!! create_label('select_data_to_duplicate', 'Select Data to Duplicate', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'users_clients_duplicate_info',
                                    'Users and Clients will be duplicated by default',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('bulk_upload', 'Bulk Upload', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('projects_bulk_upload', 'Projects Bulk Upload', Session::get('locale')) !!}
                                {!! create_label('download_sample_file', 'Download Sample File', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('drag_and_drop_file_here', 'Drag & Drop File Here', Session::get('locale')) !!}
                                {!! create_label('no_file_chosen', 'No file chosen.', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('tasks_bulk_upload', 'Tasks Bulk Upload', Session::get('locale')) !!}
                                {!! create_label('users_bulk_upload', 'Users Bulk Upload', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('help_instructions', 'Help & Instructions', Session::get('locale')) !!}
                                {!! create_label('clients_bulk_upload', 'Clients Bulk Upload', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('instructions', 'Instructions', Session::get('locale')) !!}
                                {!! create_label('leave_blank_if_no_change', 'Leave it blank if no change', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('contract_pdf', 'Contract PDF', Session::get('locale')) !!}
                                {!! create_label('unknown', 'Unknown', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('birthday_wish', 'Birthday Wish', Session::get('locale')) !!}
                                {!! create_label(
                                    'birthday_wish_email_info',
                                    'This template will be used for the email notification sent to users/clients on their birthday, and it will be sent only if their status is active.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'birthday_wish_email_will_not_sent',
                                    'If Deactive, birthday wish email notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'birthday_wish_sms_info',
                                    'This template will be used for the SMS notification sent to users/clients on their birthday, and it will be sent only if their status is active.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'birthday_wish_sms_will_not_sent',
                                    'If Deactive, birthday wish SMS notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'birthday_wish_whatsapp_info',
                                    'This template will be used for the WhatsApp notification sent to users/clients on their birthday, and it will be sent only if their status is active.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'birthday_wish_whatsapp_will_not_sent',
                                    'If Deactive, birthday wish WhatsApp notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'birthday_wish_system_info',
                                    'This template will be used for the WhatsApp notification sent to users/clients on their birthday, and it will be sent only if their status is active.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'birthday_wish_system_will_not_sent',
                                    'If Deactive, birthday wish system notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'birthday_wish_push_info',
                                    'This template will be used for the push notification sent to users/clients on their birthday, and it will be sent only if their status is active.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'birthday_wish_push_will_not_sent',
                                    'If Deactive, birthday wish push notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'birthday_wish_slack_info',
                                    'This template will be used for the slack notification sent to users/clients on their birthday, and it will be sent only if their status is active.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'birthday_wish_slack_will_not_sent',
                                    'If Deactive, birthday wish slack notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('work_anniversary_wish', 'Work Anni. Wish', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'work_anniversary_wish_email_info',
                                    'This template will be used for the email notification sent to users/clients on their work anniversary, and it will be sent only if their status is active.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'work_anniversary_wish_email_will_not_sent',
                                    'If Deactive, work anniversary wish email notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'work_anniversary_wish_sms_info',
                                    'This template will be used for the SMS notification sent to users/clients on their birthday, and it will be sent only if their status is active.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'work_anniversary_wish_whatsapp_info',
                                    'This template will be used for the WhatsApp notification sent to users/clients on their work anniversary, and it will be sent only if their status is active.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'work_anniversary_wish_whatsapp_will_not_sent',
                                    'If Deactive, work anniversary wish WhatsApp notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'work_anniversary_wish_system_info',
                                    'This template will be used for the system notification sent to users/clients on their work anniversary, and it will be sent only if their status is active.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'work_anniversary_wish_system_will_not_sent',
                                    'If Deactive, work anniversary wish system notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'work_anniversary_wish_push_info',
                                    'This template will be used for the push notification sent to users/clients on their work anniversary, and it will be sent only if their status is active.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'work_anniversary_wish_push_will_not_sent',
                                    'If Deactive, work anniversary wish push notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'work_anniversary_wish_slack_info',
                                    'This template will be used for the slack notification sent to users/clients on their work anniversary, and it will be sent only if their status is active.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'work_anniversary_wish_slack_will_not_sent',
                                    'If Deactive, work anniversary wish slack notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'work_anniversary_wish_sms_will_not_sent',
                                    'If Deactive, work anniversary wish SMS notification won\'t be sent',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'wishes_noti_info',
                                    'To send wish notifications automatically, you need to set up a cron job on your server.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('site_url', 'Site URL', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('please_enter_site_url', 'Please enter site URL', Session::get('locale')) !!}
                                {!! create_label(
                                    'enter_site_url_without_trailing_slash',
                                    'Enter the site URL without a trailing slash',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('leads_management', 'Leads Management', Session::get('locale')) !!}
                                {!! create_label('lead_sources', 'Lead Sources', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_lead_source', 'Create Lead Source', Session::get('locale')) !!}
                                {!! create_label('lead_stages', 'Lead Stages', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_lead_stage', 'Create Lead Stage', Session::get('locale')) !!}
                                {!! create_label('edit_lead_source', 'Edit Lead Source', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('select_lead_source', 'Select Lead Source', Session::get('locale')) !!}
                                {!! create_label('edit_lead_stage', 'Edit Lead Stage', Session::get('locale')) !!}
                                {!! create_label('select_lead_stage', 'Select Lead Stage', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('leads', 'Leads', Session::get('locale')) !!}
                                {!! create_label(
                                    'lead_stages_reorder_info',
                                    'Drag and drop the rows below to change the order of your lead stages.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('personal_details', 'Personal Details', Session::get('locale')) !!}
                                {!! create_label('professional_details', 'Professional Details', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('social_links', 'Social Links', Session::get('locale')) !!}
                                {!! create_label('linkedin', 'LinkedIn', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('enter_linkedin_url', 'Enter LinkedIn URL', Session::get('locale')) !!}
                                {!! create_label('instagram', 'Instagram', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('enter_instagram_url', 'Enter Instagram URL', Session::get('locale')) !!}
                                {!! create_label('facebook', 'Facebook', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('enter_facebook_url', 'Enter Facebook URL', Session::get('locale')) !!}
                                {!! create_label('pinterest', 'Pinterest', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('enter_pinterest_url', 'Enter Pinterest URL', Session::get('locale')) !!}
                                {!! create_label('industry', 'Industry', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('enter_industry', 'Enter industry', Session::get('locale')) !!}
                                {!! create_label('job_title', 'Job Title', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('enter_job_title', 'Enter job title', Session::get('locale')) !!}
                                {!! create_label('enter_company', 'Enter company name', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('enter_last_name', 'Enter Last Name', Session::get('locale')) !!}
                                {!! create_label('enter_email_address', 'Enter Email Address', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('enter_website', 'Enter company website', Session::get('locale')) !!}
                                {!! create_label('assigned_to', 'Assign To', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('enter_first_name', 'Enter First Name', Session::get('locale')) !!}
                                {!! create_label('create_lead', 'Create Lead', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('filter_by_sources', 'Filter by sources', Session::get('locale')) !!}
                                {!! create_label('lead_details', 'Lead Details', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('info', 'Info', Session::get('locale')) !!}
                                {!! create_label('follow_ups', 'Follow Ups', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('contact_information', 'Contact Information', Session::get('locale')) !!}
                                {!! create_label('full_name', 'Full Name', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('lead_source', 'Lead Source', Session::get('locale')) !!}
                                {!! create_label('lead_stage', 'Lead Stage', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('no_follow_ups_found', 'No follow-ups found', Session::get('locale')) !!}
                                {!! create_label('create_follow_up', 'Create Follow-up', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_lead_follow_up', 'Create Lead Follow Up', Session::get('locale')) !!}
                                {!! create_label('follow_up_date', 'Follow Up Date', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'follow_up_date_info',
                                    'This date will help you record when the follow-up is taken.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('follow_up_type', 'Follow Up Type', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'follow_up_type_info',
                                    'Categorize the follow-up, for example: call, email, etc.',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label(
                                    'follow_up_note_info',
                                    'Add any notes that you want to keep for this follow-up.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('edit_lead_follow_up', 'Edit Lead Follow Up', Session::get('locale')) !!}
                                {!! create_label(
                                    'create_your_first_follow_up_to_track_interactions_with_this_lead',
                                    'Create your first follow-up to track interactions with this lead.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('address_information', 'Address Information', Session::get('locale')) !!}
                                {!! create_label('google_calendar', 'Google Calendar', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label(
                                    'documentation_for_integration_with_google_calendar',
                                    'Documentation for integration with Google Calendar',
                                    Session::get('locale'),
                                ) !!}
                                {!! create_label('api_key', 'API Key', Session::get('locale')) !!}
                                {!! create_label('please_enter_your_google_api_key', 'Please Enter Your Google API Key', Session::get('locale')) !!}
                                {!! create_label('google_calendar_id', 'Google Calendar ID', Session::get('locale')) !!}
                                {!! create_label(
                                    'please_enter_your_google_calendar_id',
                                    'Please Enter Your Google Calendar ID',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('holiday_calendar', 'Holiday Calendar', Session::get('locale')) !!}
                                {!! create_label('calendars', 'Calendars', Session::get('locale')) !!}
                                {!! create_label('leave_accepted', 'Leave Accepted', Session::get('locale')) !!}
                                {!! create_label('leave_pending', 'Leave Pending', Session::get('locale')) !!}
                                {!! create_label('leave_rejected', 'Leave Rejected', Session::get('locale')) !!}
                                {!! create_label(
                                    'google_calendar_integration_missing_please_setup_in_settings',
                                    'Google Calendar integration is not set up yet. Please connect it from the settings to enable synchronization.',
                                    Session::get('locale'),
                                ) !!}
                            </div>
                            <div class="row">
                                {!! create_label('candidate', 'Candidates', Session::get('locale')) !!}
                                {!! create_label('candidate_status', 'Candidates Status', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('interviews', 'Interviews', Session::get('locale')) !!}
                                {!! create_label('source', 'Source', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('position', 'Position', Session::get('locale')) !!}
                                {!! create_label('create_candidate', 'Create Candidate', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('order', 'Order', Session::get('locale')) !!}
                                {!! create_label('candidate_status_reorder_info','Drag and drop the rows below to change the order of your candidate status.', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_candidate_status', 'Create Candidate Status', Session::get('locale')) !!}
                                {!! create_label('custom_fields', 'Custom Fields', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('create_custom_field', 'Create Custom Field', Session::get('locale')) !!}
                                {!! create_label('module','Module', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('field_label','Field Label', Session::get('locale')) !!}
                                {!! create_label('field_type','Field Type', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('required','Required', Session::get('locale')) !!}
                                {!! create_label('show_in_table','Show in Table', Session::get('locale')) !!}
                            </div>
                            <div class="row">
                                {!! create_label('task_lists', 'Task Lists', Session::get('locale')) !!}
                                {!! create_label('create_task_list', 'Create Task List', Session::get('locale')) !!}
                                {!! create_label('HRMS', 'HRMS', Session::get('locale')) !!}
                            </div>

                            <div class="row">

                                <!-- </div> -->
                                <div class="card-footer">
                                    <div class="col-sm-12">
                                        <div class="mt-4 text-center">
                                            <button type="submit" class="btn btn-primary me-2"
                                                id="submit_btn"><?= get_label('update', 'Update') ?></button>
                                        </div>
                                    </div>
                                </div>
                                <!-- </div> -->
                                <!-- </div> -->
                            </div>
                            </form>
                        </div>
                        <!--/ List group with Badges & Pills -->
                    </div>
                </div>
            </div>
        </div>
    @endsection
