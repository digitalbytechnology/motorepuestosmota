import $ from 'jquery'

// DataTables Bootstrap 4
import 'datatables.net-bs4'

// Buttons Bootstrap 4
import 'datatables.net-buttons-bs4'
import 'datatables.net-buttons/js/buttons.html5'
import 'datatables.net-buttons/js/buttons.print'
import 'datatables.net-buttons/js/buttons.colVis'

// Excel
import JSZip from 'jszip'
window.JSZip = JSZip

// PDF (esta es la forma correcta para Vite)
import pdfMake from 'pdfmake/build/pdfmake'
import pdfFonts from 'pdfmake/build/vfs_fonts'
pdfMake.vfs = pdfFonts.vfs

$(function () {
  const $table = $('#usersTable')
  if (!$table.length) return

  const dt = $table.DataTable({
    responsive: true,
    autoWidth: false,
    pageLength: 10,
    order: [[0, 'asc']], // por nombre
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
    },
    dom:
      "<'row'<'col-md-6'B><'col-md-6'f>>" +
      "<'row'<'col-12'tr>>" +
      "<'row'<'col-md-5'i><'col-md-7'p>>",
    buttons: [
      { extend: 'copy', text: 'Copiar' },
      { extend: 'excel', text: 'Excel', title: 'Usuarios' },
      { extend: 'pdf', text: 'PDF', title: 'Usuarios' },
      { extend: 'print', text: 'Imprimir', title: 'Usuarios' },
      { extend: 'colvis', text: 'Columnas' }
    ]
  })

  dt.buttons().container().appendTo('#usersTable_wrapper .col-md-6:eq(0)')
})
