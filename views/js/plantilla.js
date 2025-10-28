$(document).ready(function() {
    var table = $('.example2').DataTable({
     responsive: true,
     lengthChange: false,
     dom: 'Blfrtip',
     buttons: [
       {
       extend: 'copy',
       text: '<i class="lni lni-copy" style="color: white;"></i> Copiar',
       className: 'btn btn-warning'
       },
       {
       extend: 'excelHtml5',
       text: '<i class="lni lni-file-excel" style="color: white;"></i> Excel',
       className: 'btn btn-success'
       },
       {
       extend: 'pdfHtml5',
       text: '<i class="lni lni-file-pdf" style="color: white;"></i> PDF',
       className: 'btn btn-danger'
       },
       {
       extend: 'print',
       text: '<i class="lni lni-printer" style="color: white;"></i> Imprimir',
       className: 'btn btn-light'
       }
     ],
     language: {
       "sProcessing":     "Procesando...",
       "sLengthMenu":     "Mostrar _MENU_ registros",
       "sZeroRecords":    "No se encontraron resultados",
       "sEmptyTable":     "Ningún dato disponible en esta tabla",
       "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
       "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
       "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
       "sInfoPostFix":    "",
       "sSearch":         "Buscar:",
       "sUrl":            "",
       "sInfoThousands":  ",",
       "sLoadingRecords": "Cargando...",
       "oPaginate": {
       "sFirst":    "Primero",
       "sLast":     "Último",
       "sNext":     "Siguiente",
       "sPrevious": "Anterior"
       },
       "oAria": {
       "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
       "sSortDescending": ": Activar para ordenar la columna de manera descendente"
       }
     }
     });
 
   table.buttons().container()
       .appendTo('#example2_wrapper .col-md-6:eq(0)');
     
 });