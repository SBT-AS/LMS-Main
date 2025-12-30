@extends('backend.layouts.master')

@section('title', 'Courses Management')
@section('header_title', 'Course Management')

@push('styles')
<style>
    /* Custom Scrollbar for Table */
    .dataTables_wrapper .dataTables_scrollBody::-webkit-scrollbar {
        height: 8px;
    }
    .dataTables_wrapper .dataTables_scrollBody::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    .dataTables_wrapper .dataTables_scrollBody::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .dataTables_wrapper .dataTables_scrollBody::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* DataTable Customization */
    .dataTables_wrapper .dataTables_length select {
        padding-right: 2.5rem;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
    }
    
    table.dataTable thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #64748b;
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    table.dataTable tbody tr {
        transition: all 0.2s ease;
    }

    table.dataTable tbody tr:hover {
        background-color: #f8fafc;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    /* Floating Action Bar */
    #bulkActions {
        backdrop-filter: blur(12px);
        background-color: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(226, 232, 240, 0.8);
    }
    
    /* Pagination Styles */
    .dataTables_paginate .paginate_button {
        @apply px-3 py-1 text-sm rounded-lg hover:bg-indigo-50 hover:text-indigo-600 transition-colors cursor-pointer text-gray-500 font-medium;
    }
    .dataTables_paginate .paginate_button.current {
        @apply bg-indigo-600 text-white hover:bg-indigo-700 hover:text-white shadow-sm;
    }
    .dataTables_paginate .paginate_button.disabled {
        @apply opacity-50 cursor-not-allowed hover:bg-transparent hover:text-gray-500;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Courses Overview</h2>
            <p class="text-gray-500 text-sm mt-1">Manage and organize your educational content efficiently.</p>
        </div>
        @can('courses.create')
        <a href="{{ route('admin.courses.create') }}"
           class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-0.5">
            <i class="bi bi-plus-lg mr-2"></i> Create New Course
        </a>
        @endcan
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Courses -->
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Courses</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ \App\Models\Course::count() }}</h3>
                </div>
                <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                    <i class="bi bi-collection-play text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-gray-500">
                <span class="text-indigo-600 font-medium bg-indigo-50 px-1.5 py-0.5 rounded mr-2">
                    <i class="bi bi-arrow-up-short"></i> Live
                </span>
                <span>All time stats</span>
            </div>
        </div>

        <!-- Active Courses -->
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Courses</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ \App\Models\Course::where('status', 1)->count() }}</h3>
                </div>
                <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                    <i class="bi bi-check-circle text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-gray-500">
                <span class="text-gray-400">Published online</span>
            </div>
        </div>

        <!-- Free Courses -->
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Free Content</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ \App\Models\Course::where('price', 0)->count() }}</h3>
                </div>
                <div class="p-2 bg-purple-50 text-purple-600 rounded-lg">
                    <i class="bi bi-gift text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-gray-500">
                <span class="text-gray-400">Available mostly for leads</span>
            </div>
        </div>
        
         <!-- Live Classes -->
         <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Live Classes</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ \App\Models\Course::where('live_class', 1)->count() }}</h3>
                </div>
                <div class="p-2 bg-rose-50 text-rose-600 rounded-lg">
                    <i class="bi bi-camera-video text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-gray-500">
                <span class="text-gray-400">Interactive sessions</span>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Filter Section -->
            <div class="flex items-center gap-2">
                <div class="relative">
                    <i class="bi bi-funnel absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <select id="statusFilter" class="appearance-none pl-9 pr-8 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-700 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all cursor-pointer hover:border-indigo-300">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                
                <div class="relative">
                    <i class="bi bi-tag absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <select id="priceFilter" class="appearance-none pl-9 pr-8 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-700 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all cursor-pointer hover:border-indigo-300">
                        <option value="">All Prices</option>
                        <option value="free">Free</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>

                <div class="relative">
                    <i class="bi bi-grid absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <select id="categoryFilter" class="appearance-none pl-9 pr-8 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-700 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all cursor-pointer hover:border-indigo-300">
                        <option value="">All Categories</option>
                        @foreach(App\Models\Category::all() as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Search is prepended via JS to DataTable -->
        </div>

        <div class="p-0">
            <div class="overflow-x-auto">
                <table id="courses-table" class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="pl-6 pr-3 py-4 w-10">
                                <div class="flex items-center justify-center">
                                    <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                                </div>
                            </th>
                            <th class="px-4 py-4 font-semibold text-gray-600 text-xs uppercase tracking-wider">#</th>
                            <th class="px-4 py-4 font-semibold text-gray-600 text-xs uppercase tracking-wider">Info</th>
                            <th class="px-4 py-4 font-semibold text-gray-600 text-xs uppercase tracking-wider text-center">Category</th>
                            <th class="px-4 py-4 font-semibold text-gray-600 text-xs uppercase tracking-wider">Price</th>
                            <th class="px-4 py-4 font-semibold text-gray-600 text-xs uppercase tracking-wider text-center">Content</th>
                            <th class="px-4 py-4 font-semibold text-gray-600 text-xs uppercase tracking-wider text-center">Type</th>
                            <th class="px-4 py-4 font-semibold text-gray-600 text-xs uppercase tracking-wider text-center">Status</th>
                            <th class="px-4 py-4 font-semibold text-gray-600 text-xs uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-xs uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        <!-- DataTable will populate this -->
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Bulk Actions Bar -->
        <div id="bulkActions" class="hidden fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-white rounded-2xl shadow-2xl border border-gray-100 px-6 py-3 z-50 flex items-center gap-6">
            <div class="flex items-center gap-3 pr-4 border-r border-gray-200">
                <div class="bg-indigo-50 text-indigo-600 rounded-full w-8 h-8 flex items-center justify-center font-bold text-sm" id="selectedCount">0</div>
                <span class="text-gray-700 font-medium text-sm">Selected</span>
            </div>
            
            <div class="flex items-center gap-2">
                <button type="button" id="bulkActivate" class="group flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-green-50 text-gray-600 hover:text-green-700 transition-colors">
                    <i class="bi bi-check-circle text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm font-medium">Activate</span>
                </button>
                
                <button type="button" id="bulkDeactivate" class="group flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-yellow-50 text-gray-600 hover:text-yellow-700 transition-colors">
                    <i class="bi bi-slash-circle text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm font-medium">Deactivate</span>
                </button>
                
                <div class="h-6 w-px bg-gray-200 mx-1"></div>
                
                <button type="button" id="bulkDelete" class="group flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-red-50 text-gray-600 hover:text-red-700 transition-colors">
                    <i class="bi bi-trash text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm font-medium">Delete</span>
                </button>
            </div>
            
            <button type="button" id="clearSelection" class="ml-2 text-gray-400 hover:text-gray-600">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
var datatable;
var selectedCourses = [];

$(function () {
    // Initialize AjaxCrud
    AjaxCrud.init({
        tableId: '#courses-table',
    });

    // Initialize DataTable
    datatable = $('#courses-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        
        autoWidth: false,
            ajax: {
                url: "{{ route('admin.courses.index') }}",
                data: function (d) {
                    d.status = $('#statusFilter').val();
                    d.price = $('#priceFilter').val();
                    d.category_id = $('#categoryFilter').val();
                }
            },
        columns: [
            {
                data: 'checkbox',
                name: 'checkbox',
                orderable: false,
                searchable: false,
                className: 'pl-6 pr-3 align-middle',
                render: function(data, type, row) {
                    return '<div class="flex items-center justify-center"><input type="checkbox" name="course_checkbox[]" class="course_checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer" value="'+row.id+'" data-id="'+row.id+'"></div>';
                }
            },
            { 
                data: 'DT_RowIndex', 
                name: 'DT_RowIndex', 
                orderable: false, 
                searchable: false,
                className: 'px-4 align-middle text-gray-500 font-medium text-center'
            },
            { 
                data: 'title', 
                name: 'title',
                className: 'px-4 align-middle',
                render: function(data, type, row) {
                    let img;
                    if (row.image) {
                         img = '<img src="{{ asset("storage/courses") }}/'+row.image+'" class="w-10 h-10 rounded-lg object-cover shadow-sm border border-gray-100">';
                    } else if (row.video) {
                         img = '<div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-500 border border-indigo-100"><i class="bi bi-film"></i></div>';
                    } else {
                         img = '<div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 border border-gray-100"><i class="bi bi-image"></i></div>';
                    }
                    
                    return '<div class="flex items-center gap-3">' +
                                img +
                                '<div>' +
                                    '<div class="font-semibold text-gray-800 line-clamp-1 hover:text-indigo-600 transition-colors cursor-pointer" title="'+data+'">' + data + '</div>' +
                                '</div>' +
                           '</div>';
                }
            },
            { 
                data: 'category', 
                name: 'category',
                className: 'px-4 align-middle text-center',
                render: function(data, type, row) {
                    return '<span class="px-2.5 py-1 text-xs bg-indigo-50 text-indigo-700 rounded-full font-medium border border-indigo-100">' + data + '</span>';
                }
            },
            { 
                data: 'price', 
                name: 'price',
                className: 'px-4 align-middle',
                render: function(data, type, row) {
                    if (data == 0) {
                        return '<span class="px-2.5 py-1 text-xs bg-emerald-50 text-emerald-700 rounded-full font-medium border border-emerald-100">Free</span>';
                    }
                    return '<span class="font-medium text-gray-700">₹' + parseFloat(data).toFixed(2) + '</span>';
                }
            },
            { 
                data: null, 
                name: 'content',
                orderable: false,
                searchable: false,
                className: 'px-4 align-middle text-center',
                render: function(data, type, row) {
                    let content = '<div class="flex justify-center gap-2">';
                    if (row.materials_count > 0) {
                        content += '<span class="flex items-center gap-1 text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded-md border border-blue-100" title="Materials"><i class="bi bi-file-earmark-text"></i> ' + row.materials_count + '</span>';
                    }
                    if (row.quizzes_count > 0) {
                        content += '<span class="flex items-center gap-1 text-xs font-medium text-purple-600 bg-purple-50 px-2 py-1 rounded-md border border-purple-100" title="Quizzes"><i class="bi bi-puzzle"></i> ' + row.quizzes_count + '</span>';
                    }
                    if (row.materials_count == 0 && row.quizzes_count == 0) { 
                        content += '<span class="text-xs text-gray-400">-</span>';
                    }
                    content += '</div>';
                    return content;
                }
            },
            { 
                data: 'live_class', 
                name: 'live_class',
                className: 'px-4 align-middle text-center',
                render: function(data) {
                    return data 
                        ? '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-600 border border-rose-100"><i class="bi bi-camera-video-fill"></i> Live</span>'
                        : '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-500 border border-gray-100"><i class="bi bi-play-circle"></i> VOD</span>';
                }
            },
            { 
                data: 'status', 
                name: 'status',
                className: 'px-4 align-middle text-center',
                render: function(data) {
                    return data 
                        ? '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"><span class="w-1.5 h-1.5 rounded-full bg-green-600 mr-1.5"></span>Active</span>'
                        : '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"><span class="w-1.5 h-1.5 rounded-full bg-gray-500 mr-1.5"></span>Inactive</span>';
                }
            },
            { 
                data: 'created_at', 
                name: 'created_at',
                className: 'px-4 align-middle text-gray-500 text-xs'
            },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false,
                className: 'px-6 align-middle text-right'
            },
        ],
        order: [[7, 'desc']], // Sort by created_at by default
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: '<"p-5 flex flex-col md:flex-row justify-between items-center gap-4"<"flex items-center gap-2"l><"flex items-center gap-2"f>>rt<"p-5 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search courses...",
            lengthMenu: "Show _MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ courses",
            paginate: {
                first: '<i class="bi bi-chevron-double-left"></i>',
                last: '<i class="bi bi-chevron-double-right"></i>',
                next: '<i class="bi bi-chevron-right"></i>',
                previous: '<i class="bi bi-chevron-left"></i>'
            }
        },
        drawCallback: function(settings) {
            // Re-apply checkbox listener
            bindCheckboxEvents();
            // Re-initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });

    // Custom stylings for DataTable elements
    $('.dataTables_filter input').addClass('pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none w-64 transition-all');
    $('.dataTables_length select').addClass('border border-gray-200 rounded-lg py-2 text-sm focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none');

    // Add search icon
    $('.dataTables_filter').addClass('relative');
    if ($('.dataTables_filter .bi-search').length === 0) {
        $('.dataTables_filter').prepend('<i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 z-10"></i>');
    }

    // Apply filters
    $('#statusFilter, #priceFilter, #categoryFilter').on('change', function() {
        datatable.draw();
    });

    // Select All functionality
    $('#selectAll').on('change', function() {
        var isChecked = $(this).is(':checked');
        $('.course_checkbox').prop('checked', isChecked).trigger('change');
        
        if (isChecked) {
            selectedCourses = [];
            $('.course_checkbox').each(function() {
                 let id = $(this).data('id');
                 if (!selectedCourses.includes(id)) selectedCourses.push(id);
            });
        } else {
            selectedCourses = [];
        }
        updateBulkActions();
    });

    function bindCheckboxEvents() {
        $('.course_checkbox').off('change').on('change', function() {
            var rowId = $(this).data('id');
            if ($(this).is(':checked')) {
                if (!selectedCourses.includes(rowId)) {
                    selectedCourses.push(rowId);
                }
            } else {
                selectedCourses = selectedCourses.filter(id => id !== rowId);
                $('#selectAll').prop('checked', false);
            }
            updateBulkActions();
        });
    }

    // Bulk actions
    $('#bulkActivate').on('click', function() {
        if (selectedCourses.length > 0) bulkAction('activate', selectedCourses);
    });

    $('#bulkDeactivate').on('click', function() {
        if (selectedCourses.length > 0) bulkAction('deactivate', selectedCourses);
    });

    $('#bulkDelete').on('click', function() {
        if (selectedCourses.length > 0) {
            Swal.fire({
                title: 'Delete ' + selectedCourses.length + ' Course(s)?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete them!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-6 py-2 rounded-xl font-bold text-white bg-red-600 hover:bg-red-700 ml-3',
                    cancelButton: 'px-6 py-2 rounded-xl font-bold text-gray-700 bg-gray-100 hover:bg-gray-200'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    bulkAction('delete', selectedCourses);
                }
            });
        }
    });

    $('#clearSelection').on('click', function() {
        selectedCourses = [];
        $('.course_checkbox').prop('checked', false);
        $('#selectAll').prop('checked', false);
        updateBulkActions();
    });

    function updateBulkActions() {
        var count = selectedCourses.length;
        $('#selectedCount').text(count);
        
        if (count > 0) {
            $('#bulkActions').removeClass('hidden').addClass('flex');
        } else {
            $('#bulkActions').addClass('hidden').removeClass('flex');
        }
    }

    function bulkAction(action, ids) {
        AjaxCrud.bulkAction("{{ route('admin.courses.bulk') }}", action, ids, function(response) {
            selectedCourses = [];
            updateBulkActions();
            $('#selectAll').prop('checked', false);
        });
    }
});
</script>
@endpush
