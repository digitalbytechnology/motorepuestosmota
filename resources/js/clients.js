import $ from 'jquery'

// DataTables + Bootstrap 4
import 'datatables.net-bs4'
import 'datatables.net-buttons-bs4'

// Botones
import 'datatables.net-buttons/js/buttons.html5'
import 'datatables.net-buttons/js/buttons.print'
import 'datatables.net-buttons/js/buttons.colVis'

// Dependencias para Excel/PDF
import JSZip from 'jszip'
import pdfMake from 'pdfmake/build/pdfmake'
import pdfFonts from 'pdfmake/build/vfs_fonts'

window.JSZip = JSZip
pdfMake.vfs = pdfFonts.pdfMake.vfs

$(function () {
  const table = $('#clientsTable').DataTable({
    responsive: true,
    autoWidth: false,
    pageLength: 10,
    lengthMenu: [10, 25, 50, 100],
    order: [[0, 'desc']],
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
    },
    dom: "<'row'<'col-md-6'B><'col-md-6'f>>" +
         "<'row'<'col-12'tr>>" +
         "<'row'<'col-md-5'i><'col-md-7'p>>",
    buttons: [
      { extend: 'copy', text: 'Copiar' },
      { extend: 'excel', text: 'Excel', title: 'Clientes' },
      { extend: 'pdf', text: 'PDF', title: 'Clientes' },
      { extend: 'print', text: 'Imprimir', title: 'Clientes' },
      { extend: 'colvis', text: 'Columnas' }
    ],
  })

  // (Opcional) Mover botones al lugar típico de AdminLTE
  table.buttons().container().appendTo('#clientsTable_wrapper .col-md-6:eq(0)')
})
