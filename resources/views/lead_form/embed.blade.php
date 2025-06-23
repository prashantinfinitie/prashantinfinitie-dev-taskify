@extends('layout')

@section('title')
    {{ get_label('embed_code', 'Embed Code') }}
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb and Actions -->
        <div class="d-flex justify-content-between mb-2 mt-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{ url('home') }}">{{ get_label('home', 'Home') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span>{{ get_label('leads_management', 'Leads Management') }}</span>
                    </li>
                    <li class="breadcrumb-item">
                        {{ get_label('lead_forms', 'Lead Forms') }}
                    </li>
                    <li class="breadcrumb-item active">
                        {{ get_label('embed_code', 'Embed code') }}
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Card -->
        <div class="card rounded-4 border-0 shadow">
            <div class="card-body p-4">
                <!-- Header -->
                <h4 class="fw-semibold mb-4">{{ get_label('embed_your_form', 'Embed Your Form') }}</h4>

                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs mb-4" id="embedTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="iframe-tab" data-bs-toggle="tab"
                            data-bs-target="#iframe-content" type="button" role="tab">
                            <i class="bx bx-code-alt me-2"></i>{{ get_label('iframe_embed', 'iFrame Embed') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="widget-tab" data-bs-toggle="tab" data-bs-target="#modal-content"
                            type="button" role="tab">
                            <i class="bx bx-widget me-2"></i>{{ get_label('floating_widget', 'Floating Widget') }}
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="embedTabContent">
                    <!-- iFrame Embed Tab -->
                    <div class="tab-pane fade show active" id="iframe-content" role="tabpanel">
                        <div class="row">
                            <div class="col-lg-6">
                                <!-- Embed Code Section -->
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <label class="form-label fw-semibold mb-0">
                                            {{ get_label('iframe_embed_code', 'iFrame Embed Code') }}
                                        </label>
                                        <button class="btn btn-sm btn-primary"
                                            onclick="copyToClipboard('iframeEmbedCode', this)">
                                            <i class="bx bx-copy me-1"></i>{{ get_label('copy', 'Copy') }}
                                        </button>
                                    </div>
                                    <textarea class="form-control bg-light rounded-3 border" id="iframeEmbedCode" rows="6" readonly>{!! $leadForm->embed_code !!}</textarea>
                                    <small class="text-muted d-block mt-2">
                                        {{ get_label('iframe_description', 'Copy and paste this code into your website to embed the form as an iframe.') }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <!-- iFrame Preview -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold mb-3">
                                        {{ get_label('preview', 'Preview') }}
                                    </label>
                                    <div class="rounded-3 overflow-hidden border bg-white" style="height: 400px;">
                                        <div class="w-100 h-100">
                                            {!! str_replace('<iframe', '<iframe style="width:100%; height:100%; border:0;"', $leadForm->embed_code) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Widget Tab -->
                    <div class="tab-pane fade" id="modal-content" role="tabpanel">
                        <div class="row">
                            <div class="col-lg-6">
                                <!-- Code Sections -->
                                <div class="mb-4">
                                    <!-- HTML Section -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label fw-semibold text-danger mb-0">
                                                <i class="bx bxl-html5 me-1"></i>HTML Code
                                            </label>
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="copyToClipboard('floatingHtmlCode', this)">
                                                <i class="bx bx-copy me-1"></i>{{ get_label('copy', 'Copy') }}
                                            </button>
                                        </div>
                                        <textarea class="form-control bg-light rounded-3 border" id="floatingHtmlCode" rows="8" readonly><!-- Floating Form Button -->
<div onclick="toggleLeadForm()">
    <span>
        <a href="javascript:void(0);">
            <img src="https://via.placeholder.com/60x60/007bff/ffffff?text=📝" class="lead-form-icon" alt="{{ $leadForm->title }}">
        </a>
    </span>
</div>

<!-- Floating Form Container -->
<div id="leadFormContainer" class="lead-form-container">
    <div class="lead-form-header">
        <h5>{{ $leadForm->title }}</h5>
        <button onclick="toggleLeadForm()" class="close-btn">&times;</button>
    </div>
    <div class="lead-form-body">
        {!! str_replace(
            ['<iframe', '</iframe>'],
            ['<iframe class="lead-form-iframe"', '</iframe>'],
            $leadForm->embed_code,
        ) !!}
    </div>
</div></textarea>
                                    </div>

                                    <!-- CSS Section -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label fw-semibold text-info mb-0">
                                                <i class="bx bxl-css3 me-1"></i>CSS Code
                                            </label>
                                            <button class="btn btn-sm btn-outline-info"
                                                onclick="copyToClipboard('floatingCssCode', this)">
                                                <i class="bx bx-copy me-1"></i>{{ get_label('copy', 'Copy') }}
                                            </button>
                                        </div>
                                        <textarea class="form-control bg-light rounded-3 border" id="floatingCssCode" rows="12" readonly><style>
/* Floating Form Button */
.lead-form-icon {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    transition: all 0.3s ease;
    z-index: 1000;
}

.lead-form-icon:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
}

/* Floating Form Container */
.lead-form-container {
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 400px;
    height: 500px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    display: none;
    flex-direction: column;
    z-index: 1001;
    overflow: hidden;
}

.lead-form-container.active {
    display: flex;
}

.lead-form-header {
    padding: 15px 20px;
    background: #007bff;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.lead-form-header h5 {
    margin: 0;
    font-size: 16px;
}

.close-btn {
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background-color 0.2s;
}

.close-btn:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

.lead-form-body {
    flex: 1;
    overflow: hidden;
}

.lead-form-iframe {
    width: 100%;
    height: 100%;
    border: none;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .lead-form-container {
        width: calc(100vw - 40px);
        height: 70vh;
        bottom: 90px;
        right: 20px;
        left: 20px;
    }

    .lead-form-icon {
        width: 50px;
        height: 50px;
        bottom: 15px;
        right: 15px;
    }
}
</style></textarea>
                                    </div>

                                    <!-- JavaScript Section -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label fw-semibold text-warning mb-0">
                                                <i class="bx bxl-javascript me-1"></i>JavaScript Code
                                            </label>
                                            <button class="btn btn-sm btn-outline-warning"
                                                onclick="copyToClipboard('floatingJsCode', this)">
                                                <i class="bx bx-copy me-1"></i>{{ get_label('copy', 'Copy') }}
                                            </button>
                                        </div>
                                        <textarea class="form-control bg-light rounded-3 border" id="floatingJsCode" rows="8" readonly>


                                            <script>
                                            function toggleLeadForm() {
                                                const container = document.getElementById('leadFormContainer');

                                                if (container.classList.contains('active')) {
                                                    container.classList.remove('active');
                                                    console.log('Lead form closed');
                                                } else {
                                                    container.classList.add('active');
                                                    console.log('Lead form opened');
                                                }
                                            }

                                            // Close form when clicking outside
                                            document.addEventListener('click', function(event) {
                                                const container = document.getElementById('leadFormContainer');
                                                const icon = document.querySelector('.lead-form-icon');

                                                if (container && container.classList.contains('active')) {
                                                    if (!container.contains(event.target) && !icon.contains(event.target)) {
                                                        container.classList.remove('active');
                                                    }
                                                }
                                            });

                                            // Optional: Close with Escape key
                                            document.addEventListener('keydown', function(event) {
                                                if (event.key === 'Escape') {
                                                    const container = document.getElementById('leadFormContainer');
                                                    if (container && container.classList.contains('active')) {
                                                        container.classList.remove('active');
                                                    }
                                                }
                                            });
                                        </script></textarea>
                                    </div>

                                    <!-- Copy All Button -->
                                    <div class="text-center">
                                        <button class="btn btn-success" onclick="copyAllFloatingCode()">
                                            <i
                                                class="bx bx-copy-alt me-2"></i>{{ get_label('copy_all_code', 'Copy All Code') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <!-- Floating Widget Preview -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold mb-3">
                                        {{ get_label('preview', 'Preview') }}
                                    </label>
                                    <div class="mb-3 text-center">
                                        <button type="button" class="btn btn-primary" onclick="previewFloatingWidget()">
                                            <i
                                                class="bx bx-show me-2"></i>{{ get_label('preview_widget', 'Preview Widget') }}
                                        </button>
                                    </div>
                                    <div class="alert alert-info">
                                        <i class="bx bx-info-circle me-2"></i>
                                        <strong>{{ get_label('widget_info', 'Widget Information') }}:</strong><br>
                                        {{ get_label('widget_description', 'This creates a floating button (like a chat widget) that toggles a form container. Perfect for non-intrusive lead capture.') }}
                                    </div>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ get_label('features', 'Features') }}:</h6>
                                            <ul class="list-unstyled mb-0">
                                                <li><i
                                                        class="bx bx-check text-success me-2"></i>{{ get_label('floating_button', 'Floating Button') }}
                                                </li>
                                                <li><i
                                                        class="bx bx-check text-success me-2"></i>{{ get_label('responsive_design', 'Responsive Design') }}
                                                </li>
                                                <li><i
                                                        class="bx bx-check text-success me-2"></i>{{ get_label('click_outside_close', 'Click Outside to Close') }}
                                                </li>
                                                <li><i
                                                        class="bx bx-check text-success me-2"></i>{{ get_label('escape_key_close', 'Escape Key to Close') }}
                                                </li>
                                                <li><i
                                                        class="bx bx-check text-success me-2"></i>{{ get_label('customizable_icon', 'Customizable Icon') }}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Floating Widget -->
    <div id="previewFloatingWidget" style="display: none;">
        <div onclick="togglePreviewLeadForm()">
            <span>
                <a href="javascript:void(0);">
                    <img src="{{ asset('assets/img/form.png') }}" class="embed-icon"
                        alt="{{ $leadForm->title }}">
                </a>
            </span>
        </div>
        <div id="previewLeadFormContainer" class="preview-lead-form-container">
            <div class="lead-form-header">
                <h5>{{ $leadForm->title }}</h5>
                <button onclick="togglePreviewLeadForm()" class="close-btn">&times;</button>
            </div>
            <div class="lead-form-body">
                {!! str_replace(
                    ['<iframe', '</iframe>'],
                    ['<iframe class="lead-form-iframe"', '</iframe>'],
                    $leadForm->embed_code,
                ) !!}
            </div>
        </div>
    </div>

    <style>
        .embed-container {
            position: relative;
            width: 100%;
            height: 500px;
            overflow: hidden;
        }

        .modal-iframe {
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 0.375rem;
        }

        .modal-lg {
            max-width: 800px;
        }

        /* Preview Floating Widget Styles */
        .preview-lead-form-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
            transition: all 0.3s ease;
            z-index: 1050;
        }

        .preview-lead-form-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        }

        .preview-lead-form-container {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 400px;
            height: 500px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            display: none;
            flex-direction: column;
            z-index: 1051;
            overflow: hidden;
        }

        .preview-lead-form-container.active {
            display: flex;
        }

        .lead-form-header {
            padding: 15px 20px;
            /* background: #007bff; */
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .lead-form-header h5 {
            margin: 0;
            font-size: 16px;
        }

        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.2s;
        }

        .close-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .lead-form-body {
            flex: 1;
            overflow: hidden;
        }

        .lead-form-iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        @media (max-width: 768px) {
            .embed-container {
                height: 400px;
            }

            .preview-lead-form-container {
                width: calc(100vw - 40px);
                height: 70vh;
                bottom: 90px;
                right: 20px;
                left: 20px;
            }

            .preview-lead-form-icon {
                width: 50px;
                height: 50px;
                bottom: 15px;
                right: 15px;
            }
        }

        .nav-tabs .nav-link {
            border: none;
            border-bottom: 2px solid transparent;
            background: none;
            color: #6c757d;
            padding: 0.75rem 1.5rem;
        }

        .nav-tabs .nav-link:hover {
            border-color: transparent;
            color: #495057;
            background-color: #f8f9fa;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom-color: #0d6efd;
            background-color: transparent;
        }

        /* .tab-content {
            padding-top: 1rem;
        } */
    </style>

    <script>
        // Copy to clipboard function
        function copyToClipboard(elementId, button) {
            const textarea = document.getElementById(elementId);
            textarea.select();
            textarea.setSelectionRange(0, 99999); // For mobile devices

            navigator.clipboard.writeText(textarea.value).then(function() {
                // Store original button content
                const originalContent = button.innerHTML;

                // Change button to show success
                button.innerHTML = '<i class="bx bx-check me-1"></i>{{ get_label('copied', 'Copied') }}';
                button.classList.remove('btn-primary', 'btn-outline-danger', 'btn-outline-info',
                    'btn-outline-warning');
                button.classList.add('btn-success');

                // Reset button after 2 seconds
                setTimeout(() => {
                    button.innerHTML = originalContent;
                    button.classList.remove('btn-success');

                    // Add back the appropriate class based on the button type
                    if (elementId.includes('Html')) {
                        button.classList.add('btn-outline-danger');
                    } else if (elementId.includes('Css')) {
                        button.classList.add('btn-outline-info');
                    } else if (elementId.includes('Js')) {
                        button.classList.add('btn-outline-warning');
                    } else {
                        button.classList.add('btn-primary');
                    }
                }, 2000);
            }).catch(function(err) {
                console.error('Failed to copy text: ', err);

                // Fallback for older browsers
                textarea.select();
                document.execCommand('copy');

                const originalContent = button.innerHTML;
                button.innerHTML = '<i class="bx bx-check me-1"></i>{{ get_label('copied', 'Copied') }}';
                button.classList.add('btn-success');

                setTimeout(() => {
                    button.innerHTML = originalContent;
                    button.classList.remove('btn-success');
                    button.classList.add('btn-primary');
                }, 2000);
            });
        }

        // Copy all floating code
        function copyAllFloatingCode() {
            const htmlCode = document.getElementById('floatingHtmlCode').value;
            const cssCode = document.getElementById('floatingCssCode').value;
            const jsCode = document.getElementById('floatingJsCode').value;

            const allCode = `${htmlCode}\n\n${cssCode}\n\n${jsCode}`;

            navigator.clipboard.writeText(allCode).then(function() {
                const button = event.target.closest('button');
                const originalContent = button.innerHTML;

                button.innerHTML =
                    '<i class="bx bx-check me-2"></i>{{ get_label('all_code_copied', 'All Code Copied') }}';
                button.classList.remove('btn-success');
                button.classList.add('btn-success');

                setTimeout(() => {
                    button.innerHTML = originalContent;
                }, 2000);
            }).catch(function(err) {
                console.error('Failed to copy all code: ', err);
            });
        }

        // Preview floating widget
        function previewFloatingWidget() {
            const previewWidget = document.getElementById('previewFloatingWidget');
            if (previewWidget.style.display === 'none') {
                previewWidget.style.display = 'block';
                event.target.innerHTML = '<i class="bx bx-hide me-2"></i>{{ get_label('hide_preview', 'Hide Preview') }}';
                event.target.classList.remove('btn-primary');
                event.target.classList.add('btn-secondary');
            } else {
                previewWidget.style.display = 'none';
                // Close the form if it's open
                const container = document.getElementById('previewLeadFormContainer');
                if (container) {
                    container.classList.remove('active');
                }
                event.target.innerHTML =
                    '<i class="bx bx-show me-2"></i>{{ get_label('preview_widget', 'Preview Widget') }}';
                event.target.classList.remove('btn-secondary');
                event.target.classList.add('btn-primary');
            }
        }

        // Toggle preview lead form
        function togglePreviewLeadForm() {
            const container = document.getElementById('previewLeadFormContainer');

            if (container.classList.contains('active')) {
                container.classList.remove('active');
            } else {
                container.classList.add('active');
            }
        }

        // Initialize tooltips if Bootstrap is available
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof bootstrap !== 'undefined') {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        });
    </script>

    <script src="{{ asset('assets/js/pages/lead-form.js') }}"></script>
@endsection
