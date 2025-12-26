/**
 * Admin Panel Main JS
 * Common functionality for all admin pages
 */

$(document).ready(function () {
    // CSRF Token Setup for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Explicitly Initialize Select2 for .init_select2 class
    if ($.fn.select2) {
        $('.init_select2').select2({
            width: '100%',
            allowClear: true,
            placeholder: function () {
                return $(this).data('placeholder');
            }
        });
    }

    // Initialize AjaxCrud for global components if available
    if (typeof AjaxCrud !== 'undefined') {
        AjaxCrud.init();
    } else if ($.fn.select2) {
        // Fallback if AjaxCrud is not loaded - Initialize a broader set of selects
        // We exclude DataTables length menu and anything with .no-select2
        $('select:not(.no-select2):not([name$="_length"])').select2({
            placeholder: "Select option",
            allowClear: true,
            width: '100%'
        });
    }

    // Generic Form Save Handler
    $('#saveBtn').on('click', function (e) {
        let form = $('#crudForm');

        if (form.length === 0) return;

        // Native browser validation check
        if (!form[0].checkValidity()) {
            form[0].reportValidity();
            return;
        }

        e.preventDefault();

        let btn = $(this);
        let originalContent = btn.html();

        // Disable button and show loading state
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

        let url = form.attr('action');
        let formData = new FormData(form[0]);

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                    title: response.success,
                    customClass: {
                        popup: "toast-success"
                    }
                });

                form[0].reset();

                if (response.url) {
                    setTimeout(() => {
                        window.location.href = response.url;
                    }, 800);
                } else {
                    btn.prop('disabled', false).html(originalContent);
                }
            },
            error: function (xhr) {
                btn.prop('disabled', false).html(originalContent);

                let message = 'Something went wrong';
                if (xhr.status === 422) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }

                Swal.fire({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    html: message,
                    customClass: {
                        popup: "toast-error"
                    }
                });
            }
        });
    });
});
