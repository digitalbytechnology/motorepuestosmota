document.addEventListener('DOMContentLoaded', () => {
  const el = document.getElementById('vehiclesTable')
  if (!el) return

  $('#vehiclesTable').DataTable({
    responsive: true,
    autoWidth: false,
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
    },
    dom: 'Bfrtip',
    buttons: ['copy', 'excel', 'pdf', 'print', 'colvis']
  })

  // Confirmación delete
  document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', (e) => {
      if (!confirm('¿Eliminar este vehículo?')) e.preventDefault()
    })
  })
})
