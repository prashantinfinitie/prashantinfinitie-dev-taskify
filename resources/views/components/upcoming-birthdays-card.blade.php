<!-- projects card -->
<div class="align-items-baseline d-flex gap-1">
    <div class="col-md-3 mb-4">
        <select class="form-select users_select" id="birthday_user_filter" aria-label="Default select example" data-placeholder="<?= get_label('select_members', 'Select Members') ?>" multiple>
        </select>
    </div>
    <div class="col-md-3 mb-4">
        <select class="form-select clients_select" id="birthday_client_filter" aria-label="Default select example" data-placeholder="<?= get_label('select_clients', 'Select Clients') ?>" multiple>
        </select>
    </div>
    <div class="col-md-4">
        <div class="input-group input-group-merge">
            <input type="number" id="upcoming_days_bd" name="upcoming_days" class="form-control" min="0" placeholder="<?= get_label('till_upcoming_days_def_30', 'Till upcoming days : default 30') ?>" autocomplete="off">
        </div>
    </div>
    <div class="col-md-2">
        <div>
            <button type="button" id="upcoming_days_birthday_filter" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="<?= get_label('filter', 'Filter') ?>"><i class='bx bx-filter-alt'></i></button>
        </div>
    </div>
</div>
<div class="table-responsive text-nowrap">
    <input type="hidden" id="data_type" value="users">
    <input type="hidden" id="data_table" value="birthdays_table">
    <input type="hidden" id="data_reload" value="1">
    <input type="hidden" id="multi_select" value="upcoming-bd">
    <table id="birthdays_table" data-toggle="table" data-loading-template="loadingTemplate" data-url="{{ url('/home/upcoming-birthdays') }}" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="dob" data-sort-order="asc" data-mobile-responsive="true" data-query-params="queryParamsUpcomingBirthdays">
        <thead>
            <tr>
                <th data-field="id"><?= get_label('id', 'ID') ?></th>
                <th data-field="member"><?= get_label('whose', 'Whose') ?></th>
                <th data-field="type"><?= get_label('type', 'Type') ?></th>
                <th data-field="dob" data-sortable="true"><?= get_label('birth_day_date', 'Birth day date') ?></th>
                <!-- <th data-field="age" data-visible="false"><?= get_label('birthday_count', 'Birthday count') ?></th>  -->
                <th data-field="days_left"><?= get_label('days_left', 'Days left') ?></th>
            </tr>
        </thead>
    </table>
</div>
