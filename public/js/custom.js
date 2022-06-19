jQuery(function($) {'use strict';
$(document).ready(function (){
	var table = $('#list-student-table').DataTable({
            dom: 'Blrtip', //Bfrtip
            responsive: true,
            bSort : true,
            pageLength:25,
            language: { search: "" },
            processing: true,
            serverSide: true,
            ajax: {
            	headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'list-student-details',
                data: function (d) {
                },
                method: 'POST'
            },
             
            "dataType": "jsonp",
            "columns": [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name', orderable: false, searchable: false },
                { data: 'email', name: 'email', orderable: false, searchable: false },
                { data: 'age', name: 'age', orderable: false, searchable: false },
                { data: 'gender', name: 'gender', orderable: false, searchable: false },
                { data: 'reporting_to', name: 'reporting_to', orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
                
            	],
                createdRow: function(row, data, index) {
                    $('td', row).eq(2).addClass('text-right');
                },
                "order":[[1, 'desc']],
                "columnDefs": [
                    {"defaultContent": "-","targets": "_all"}
                ],
                buttons: [
                ],

        });
	var Termtable = $('#list-terms-table').DataTable({
            dom: 'Blrtip', //Bfrtip
            responsive: true,
            bSort : true,
            pageLength:25,
            language: { search: "" },
            processing: true,
            serverSide: true,
            ajax: {
            	headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'list-term-details',
                data: function (d) {
                },
                method: 'POST'
            },
             
            "dataType": "jsonp",
            "columns": [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'term', name: 'term', orderable: false, searchable: false },
                { data: 'subject', name: 'subject', orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at', orderable: false, searchable: false },
           
            	],
                createdRow: function(row, data, index) {
                    $('td', row).eq(2).addClass('text-right');
                },
                "order":[[1, 'desc']],
                "columnDefs": [
                    {"defaultContent": "-","targets": "_all"}
                ],
                buttons: [
                ],

        });
    $('#list-student-marks-table').DataTable({ dom: 'Blrtip', responsive: true, bSort : false, pageLength: 10, language: { search:''}});
    $('form.mark_delete').submit(function() {
        var c = confirm("Click OK to continue?");
        return c;
    });
    $(document).on("submit","form.user_delete", function () {
        var c = confirm("Click OK to continue?");
        return c;
    });
});
});