function initTailwindDataTable(tableId, ajaxUrl, columns) {
    return $(tableId).DataTable({
        processing: true,
        serverSide: true,
        ajax: ajaxUrl,
        columns: columns,
        dom: '<"flex flex-col sm:flex-row justify-between items-center mb-4 gap-4"lf>rt<"flex flex-col sm:flex-row justify-between items-center mt-4 gap-4"ip>',
        language: {
            search: "",
            searchPlaceholder: "Search...",
            lengthMenu: "Show _MENU_ entries"
        },
        initComplete: function () {
            $('.dataTables_filter input')
                .addClass('px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-64');

            $('.dataTables_length select')
                .addClass('px-8 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500');
        }
    });
}
