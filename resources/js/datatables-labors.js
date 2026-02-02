document.addEventListener('DOMContentLoaded', () => {
  const el = document.getElementById('laborsTable')
  if (!el) return

  $('#laborsTable').DataTable({
    responsive: true,
    autoWidth: false,
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
    }
  })
})
