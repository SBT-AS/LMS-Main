/**
 * Generic AJAX CRUD Handler with specific support for:
 * - DataTables (reload on success)
 * - SweetAlert2 (confirmations & notifications)
 * - Tailwind Modals (open/close logic)
 * - Laravel Validation Errors
 */

const AjaxCrud = {
    config: {
        tableId: '#dataTable',
        createModalId: '#createModal',
        editModalId: '#editModal', // Can be same as createModalId if reusing
        modalTitleId: '#modalTitle', // ID of title element in modal
        modalContentId: '#modalContent', // ID of content container in modal
        createBtnId: '#createBtn',
        formId: '#ajaxForm', // Generic form ID, or we can find form inside modal
    },

    init: function (userConfig) {
        this.config = { ...this.config, ...userConfig };
        this.bindEvents();
        this.select2(); // Initialize on page load
    },

    eventsBound: false,

    bindEvents: function () {
        if (this.eventsBound) return;
        const self = this;

        // Open Create Modal
        $(document).on('click', this.config.createBtnId, function () {
            const url = $(this).data('url');
            const title = $(this).data('title') || 'Create New';

            self.openModal(title);
            self.loadContent(url);
        });

        // Edit Button (Delegated)
        $(document).on('click', '.edit-btn', function () {
            const url = $(this).data('url');
            const title = $(this).data('title') || 'Edit Record';

            self.openModal(title);
            self.loadContent(url);
        });

        // Delete Button (Delegated)
        $(document).on('click', '.delete-btn', function () {
            const url = $(this).data('url');
            const tableId = $(this).data('table');
            self.confirmDelete(url, tableId);
        });

        // Form Submission (Delegated to handle dynamic forms)
        $(document).on('submit', this.config.formId, function (e) {
            e.preventDefault();
            self.submitForm($(this));
        });

        this.eventsBound = true;
    },

    openModal: function (title) {
        // Tailwind specific toggle
        $(this.config.createModalId).removeClass('hidden');
        if (this.config.modalTitleId) {
            $(this.config.modalTitleId).text(title);
        }
        // Show loader
        if (this.config.modalContentId) {
            $(this.config.modalContentId).html('<div class="flex justify-center p-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div></div>');
        }
    },

    closeModal: function () {
        $(this.config.createModalId).addClass('hidden');
        if (this.config.modalContentId) {
            $(this.config.modalContentId).html('');
        }
    },

    loadContent: function (url) {
        const self = this;
        $.get(url, function (response) {
            $(self.config.modalContentId).html(response);
            self.select2(self.config.modalContentId); // Initialize after AJAX load
        }).fail(function () {
            $(self.config.modalContentId).html('<div class="text-red-500 p-4">Failed to load content.</div>');
        });
    },

    submitForm: function ($form) {
        const self = this;
        const url = $form.attr('action');
        const method = $form.attr('method');
        const data = $form.serialize();

        // Clear previous errors
        $('.error-text').text('');
        $form.find('input, select, textarea').removeClass('border-red-500');

        $.ajax({
            url: url,
            method: method,
            data: data,
            success: function (response) {
                if (response.success) {
                    self.closeModal();
                    $(self.config.tableId).DataTable().ajax.reload(null, false);

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.success,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, val) {
                        const errorSpan = $form.find('span.' + key + '_error');
                        if (errorSpan.length) {
                            errorSpan.text(val[0]);
                        } else {
                            $form.find('[name="' + key + '"]').next('.error-text').text(val[0]);
                        }
                        $form.find('[name="' + key + '"]').addClass('border-red-500');
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Something went wrong!',
                    });
                }
            }
        });
    },

    confirmDelete: function (url, tableId) {
        const self = this;
        const targetTable = tableId ? (tableId.startsWith('#') ? tableId : '#' + tableId) : self.config.tableId;

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.success,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                if ($.fn.DataTable && $.fn.DataTable.isDataTable(targetTable)) {
                                    $(targetTable).DataTable().ajax.reload(null, false);
                                } else {
                                    location.reload();
                                }
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire('Error!', xhr.responseJSON?.message || 'Something went wrong.', 'error');
                    }
                });
            }
        });
    },

    bulkAction: function (url, action, ids, callback) {
        const self = this;

        Swal.fire({
            title: 'Processing...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                action: action,
                ids: ids
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    $(self.config.tableId).DataTable().ajax.reload(null, false);

                    if (typeof callback === 'function') {
                        callback(response);
                    }
                }
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: xhr.responseJSON?.message || 'Something went wrong!',
                });
            }
        });
    },
    // select2 initialization
    select2: function (parent = 'body') {
        if ($.fn.select2) {
            const $parent = $(parent);
            $('select:not(.no-select2):not([name$="_length"])', $parent).each(function () {
                const $this = $(this);
                // Don't re-initialize if it's already initialized
                if ($this.hasClass('select2-hidden-accessible')) return;

                const isModal = $this.closest('.modal').length > 0 || $this.closest('[role="dialog"]').length > 0;

                $this.select2({
                    width: '100%',
                    placeholder: $this.attr('placeholder') || $this.data('placeholder') || 'Select option',
                    allowClear: true,
                    dropdownParent: isModal ? $this.closest('.modal, [role="dialog"]') : null
                });
            });
        }
    }
};
