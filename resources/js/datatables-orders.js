import $ from 'jquery'
import 'datatables.net-bs4'
import 'datatables.net-buttons-bs4'
import 'datatables.net-responsive-bs4'
import jszip from 'jszip'
import pdfMake from 'pdfmake/build/pdfmake'
import pdfFonts from 'pdfmake/build/vfs_fonts'

pdfMake.vfs = pdfFonts.pdfMake.vfs
window.JSZip = jszip

document.addEventListener('DOMContentLoaded', () => {
  const el = document.getElementById('ordersTable')
  if (!el) return

  $('#ordersTable').DataTable({
    responsive: true,
    autoWidth: false,
    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json' },
    dom: 'Bfrtip',
    buttons: ['copy', 'excel', 'pdf', 'print', 'colvis'],
    order: [[0, 'desc']],
  })
})
s