DataTableOption = {
    initDataTable(table, url, order = [0, 'asc']) {
        let tableName = '#' + table;
        let indexLastColumn = $(tableName).find('tr')[0].cells.length-1;
        let table_init = $(tableName).DataTable({
            dom: 'Bfrtip',
            responsive: true,
            order: [order],
            language: {search: "", searchPlaceholder: "Search"},
            processing: true,
            serverSide: true,
            info: true,
            pageLength: 10,
            autoWidth: false,
            ajax: {
                url: url,
                type: "get",
                error: function () {
                    $(tableName).css("display", "none");
                }
            },
            drawCallback: function () {
                $('.dt-buttons > .btn').addClass('btn-outline-light btn-sm');
            },
            columnDefs: [ {
                targets: indexLastColumn,
                orderable: false
            } ],
        });
    },


}
