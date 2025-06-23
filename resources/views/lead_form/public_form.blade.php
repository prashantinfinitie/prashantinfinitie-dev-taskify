<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('assets/') }}" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') - {{ $general_settings['company_title'] ?? 'Taskify' }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset($general_settings['favicon'] ?? 'storage/logos/default_favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/google-fonts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/toastr.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@24.3.4/build/css/intlTelInput.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@24.3.4/build/js/intlTelInput.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }
        .card {
            border-radius: 12px;
            border: none;
        }
        .form-control, .form-select {
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 6px;
        }
        .btn-primary {
            border-radius: 24px;
            font-weight: 500;
            padding: 0.6rem 1.5rem;
        }
        .form-section {
            background-color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
    </style>

    <script>
        var baseUrl = "{{ url('/') }}";
        var currencySymbol = "{{ $general_settings['currency_symbol'] ?? '' }}";
    </script>

    @laravelPWA
</head>

<body>
    <div class="container py-5">
        <div class="form-section mx-auto" style="max-width: 700px;">
            <div class="text-center mb-4">
                <h2 class="h5 fw-semibold text-dark">{{ $form->title }}</h2>
                {{-- @if($form->description)
                    <p class="text-muted small mb-3">{{ $form->description }}</p>
                @endif --}}
            </div>

            <form id="leadForm" action="{{ route('public.form.submit', $form->slug) }}" method="POST">
                @csrf
                @foreach($form->leadFormFields as $field)
                    <div class="mb-4">
                        <label for="{{ $field->name ?: 'field_' . $field->id }}" class="form-label">
                            {{ $field->label }} {!! $field->is_required ? '<span class="text-danger">*</span>' : '' !!}
                        </label>

                        @if($field->type == 'textarea')
                            <textarea class="form-control" id="{{ $field->name ?: 'field_' . $field->id }}" name="{{ $field->name ?: 'field_' . $field->id }}" placeholder="{{ $field->placeholder ?? '' }}" {{ $field->is_required ? 'required' : '' }}></textarea>

                        @elseif($field->type == 'select')
                            <select class="form-select" id="{{ $field->name ?: 'field_' . $field->id }}" name="{{ $field->name ?: 'field_' . $field->id }}" {{ $field->is_required ? 'required' : '' }}>
                                <option value="">{{ $field->placeholder ?? 'Select an option' }}</option>
                                @foreach(json_decode($field->options, true) ?? [] as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>

                        @elseif($field->type == 'checkbox')
                            @if(json_decode($field->options, true))
                                @foreach(json_decode($field->options, true) as $option)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="{{ $field->name ?: 'field_' . $field->id }}_{{ $loop->index }}" name="{{ $field->name ?: 'field_' . $field->id }}[]" value="{{ $option }}" {{ $field->is_required ? 'data-required="true"' : '' }}>
                                        <label class="form-check-label" for="{{ $field->name ?: 'field_' . $field->id }}_{{ $loop->index }}">{{ $option }}</label>
                                    </div>
                                @endforeach
                            @else
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="{{ $field->name ?: 'field_' . $field->id }}" name="{{ $field->name ?: 'field_' . $field->id }}" value="1" {{ $field->is_required ? 'required' : '' }}>
                                    <label class="form-check-label" for="{{ $field->name ?: 'field_' . $field->id }}">{{ $field->placeholder ?? $field->label }}</label>
                                </div>
                            @endif

                        @elseif($field->type == 'radio')
                            @foreach(json_decode($field->options, true) ?? [] as $option)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" id="{{ $field->name ?: 'field_' . $field->id }}_{{ $loop->index }}" name="{{ $field->name ?: 'field_' . $field->id }}" value="{{ $option }}" {{ $field->is_required ? 'data-required="true"' : '' }}>
                                    <label class="form-check-label" for="{{ $field->name ?: 'field_' . $field->id }}_{{ $loop->index }}">{{ $option }}</label>
                                </div>
                            @endforeach

                        @else
                            <input type="{{ $field->type }}" class="form-control" id="{{ $field->name ?: 'field_' . $field->id }}" name="{{ $field->name ?: 'field_' . $field->id }}" placeholder="{{ $field->placeholder ?? '' }}" {{ $field->is_required ? 'required' : '' }}>
                        @endif

                        <div class="text-danger small mt-1 error-message" id="error_{{ $field->name ?: 'field_' . $field->id }}"></div>
                    </div>
                @endforeach

                <button type="submit" class="btn btn-primary w-100">Submit</button>
            </form>
        </div>
    </div>
<!-- Toast Container -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">
    <div id="formToast" class="toast align-items-center text-white bg-primary" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.4.0/dist/axios.min.js"></script>
    <script src="{{ asset('assets/js/pages/public-form.js') }}"></script>
</body>
</html>
