?>
@extends('layout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Create New Lead Form</h4>
                </div>
                <div class="card-body">
                    <form action="" method="POST" id="leadFormCreate">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title">Form Title</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="source_id">Lead Source</label>
                                    <select class="form-control" id="source_id" name="source_id" required>
                                        <option value="">Select Source</option>
                                        @foreach($sources as $source)
                                            <option value="{{ $source->id }}">{{ $source->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="stage_id">Initial Stage</label>
                                    <select class="form-control" id="stage_id" name="stage_id" required>
                                        <option value="">Select Stage</option>
                                        @foreach($stages as $stage)
                                            <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="assigned_to">Assign To</label>
                                    <select class="form-control" id="assigned_to" name="assigned_to" required>
                                        <option value="">Select User</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->first_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>

                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5>Additional Form Fields</h5>
                                <button type="button" class="btn btn-primary btn-sm" onclick="addField()">Add Field</button>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <strong>Note:</strong> The following fields are mandatory and will be automatically added: First Name, Last Name, Email, Phone, Company
                                </div>
                                <div id="fieldsContainer">
                                    <!-- Dynamic fields will be added here -->
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-success">Create Form</button>
                            <a href="{{ route('lead-forms.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let fieldIndex = 0;

function addField() {
    const container = document.getElementById('fieldsContainer');
    const fieldHtml = `
        <div class="field-row border p-3 mb-3" id="field-${fieldIndex}">
            <div class="row">
                <div class="col-md-3">
                    <label>Field Label</label>
                    <input type="text" class="form-control" name="fields[${fieldIndex}][label]" required>
                </div>
                <div class="col-md-2">
                    <label>Field Type</label>
                    <select class="form-control" name="fields[${fieldIndex}][type]" onchange="toggleOptions(${fieldIndex})" required>
                        <option value="text">Text</option>
                        <option value="textarea">Textarea</option>
                        <option value="select">Select</option>
                        <option value="checkbox">Checkbox</option>
                        <option value="radio">Radio</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Map to Lead Field</label>
                    <select class="form-control" name="fields[${fieldIndex}][name]" onchange="toggleMapping(${fieldIndex})">
                        <option value="">Custom Field</option>
                        <option value="website">Website</option>
                        <option value="job_title">Job Title</option>
                    </select>
                    <input type="hidden" name="fields[${fieldIndex}][is_mapped]" value="0" id="is_mapped_${fieldIndex}">
                </div>
                <div class="col-md-2">
                    <label>Required</label>
                    <select class="form-control" name="fields[${fieldIndex}][is_required]">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Options (for select/radio)</label>
                    <textarea class="form-control" name="fields[${fieldIndex}][options]" placeholder="Option 1, Option 2, Option 3" style="display:none;" id="options_${fieldIndex}"></textarea>
                </div>
                <div class="col-md-1">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-sm d-block" onclick="removeField(${fieldIndex})">Remove</button>
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', fieldHtml);
    fieldIndex++;
}

function removeField(index) {
    document.getElementById(`field-${index}`).remove();
}

function toggleOptions(index) {
    const typeSelect = document.querySelector(`select[name="fields[${index}][type]"]`);
    const optionsTextarea = document.getElementById(`options_${index}`);

    if (typeSelect.value === 'select' || typeSelect.value === 'radio') {
        optionsTextarea.style.display = 'block';
    } else {
        optionsTextarea.style.display = 'none';
    }
}

function toggleMapping(index) {
    const nameSelect = document.querySelector(`select[name="fields[${index}][name]"]`);
    const isMappedInput = document.getElementById(`is_mapped_${index}`);

    if (nameSelect.value) {
        isMappedInput.value = '1';
    } else {
        isMappedInput.value = '0';
    }
}
</script>
@endsection
