$(function(){
   //$("table").colResizable();
   var table = $('table').dataTable( {
        bDestroy : true,
        columnDefs: [
            { "width": "20%", "targets": 0 }
        ],
        bSortable: true,
        sPaginationType: "full_numbers",
        autoFill: true,
        aaSorting: [],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todo"]],
        colReorder: true,
        dom: 'Blfrtip',
        buttons: [
        {
            extend: 'copy',
            text: 'copiar',
            className: 'btn btn-default btn-xs'
        },
       {
            extend: 'csv',
            text: 'csv',
            className: 'btn btn-default btn-xs'
        },
        {
            extend: 'excel',
            text: 'excel',
            className: 'btn btn-default btn-xs'
        },
        {
            extend: 'pdf',
            text: 'pdf',
            className: 'btn btn-default btn-xs'
        },
        {
            extend: 'print',
            text: 'imprimir',
            className: 'btn btn-default btn-xs'
        },
        
        ],
        language: {
            "url": "/Json/Spanish.json"
        }
    } );
});
           
            
           