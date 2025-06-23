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
document.addEventListener('click', function (event) {
    const container = document.getElementById('leadFormContainer');
    const icon = document.querySelector('.lead-form-icon');

    if (container && container.classList.contains('active')) {
        if (!container.contains(event.target) && !icon.contains(event.target)) {
            container.classList.remove('active');
        }
    }
});

// Optional: Close with Escape key
document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        const container = document.getElementById('leadFormContainer');
        if (container && container.classList.contains('active')) {
            container.classList.remove('active');
        }
    }
});

// Copy to clipboard function
function copyToClipboard(elementId, button) {
    const textarea = document.getElementById(elementId);
    textarea.select();
    textarea.setSelectionRange(0, 99999); // For mobile devices

    navigator.clipboard.writeText(textarea.value).then(function () {
        // Store original button content
        const originalContent = button.innerHTML;

        // Change button to show success
        button.innerHTML = '<i class="bx bx-check me-1"></i>' + window.AppLabels.all_code_copied;
        button.classList.remove('btn-primary', 'btn-outline-danger', 'btn-outline-info', 'btn-outline-warning');
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
    }).catch(function (err) {
        console.error('Failed to copy text: ', err);

        // Fallback for older browsers
        textarea.select();
        document.execCommand('copy');

        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="bx bx-check me-1"></i>' + window.AppLabels.all_code_copied;
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

    navigator.clipboard.writeText(allCode).then(function () {
        const button = event.target.closest('button');
        const originalContent = button.innerHTML;

        button.innerHTML = '<i class="bx bx-check me-2"></i>' + window.AppLabels.all_code_copied;
        button.classList.remove('btn-success');
        button.classList.add('btn-success');

        setTimeout(() => {
            button.innerHTML = originalContent;
        }, 2000);
    }).catch(function (err) {
        console.error('Failed to copy all code: ', err);
    });
}

// Preview floating widget
function previewFloatingWidget() {
    const previewWidget = document.getElementById('previewFloatingWidget');
    if (previewWidget.style.display === 'none') {
        previewWidget.style.display = 'block';
        event.target.innerHTML = '<i class="bx bx-hide me-2"></i>' + window.AppLabels.hide_preview;
        event.target.classList.remove('btn-primary');
        event.target.classList.add('btn-secondary');
    } else {
        previewWidget.style.display = 'none';
        // Close the form if it's open
        const container = document.getElementById('previewLeadFormContainer');
        if (container) {
            container.classList.remove('active');
        }
        event.target.innerHTML = '<i class="bx bx-show me-2"></i>' + window.AppLabels.preview_widget;
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
document.addEventListener('DOMContentLoaded', function () {
    if (typeof bootstrap !== 'undefined') {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
