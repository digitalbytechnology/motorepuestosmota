function showDamagesAlert(msg, type = 'danger') {
  const el = document.getElementById('damagesAlert')
  if (!el) return
  el.className = `alert alert-${type}`
  el.textContent = msg
  el.classList.remove('d-none')
}

function hideDamagesAlert() {
  const el = document.getElementById('damagesAlert')
  if (!el) return
  el.classList.add('d-none')
}

function csrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
}

async function loadDamages(orderId) {
  // Endpoint GET para traer daños existentes (si no lo tienes, lo creamos)
  const res = await fetch(`/orders/${orderId}/damages`, {
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })

  if (!res.ok) throw new Error(`Error cargando daños (${res.status})`)
  return await res.json()
}

function renderPhotos(photos = []) {
  const wrap = document.getElementById('damagesPhotosPreview')
  if (!wrap) return
  wrap.innerHTML = ''

  photos.forEach(p => {
    const a = document.createElement('a')
    a.href = p.url
    a.target = '_blank'
    a.rel = 'noreferrer'

    const img = document.createElement('img')
    img.src = p.url
    img.style.width = '120px'
    img.style.height = '120px'
    img.style.objectFit = 'cover'
    img.style.borderRadius = '8px'
    img.style.border = '1px solid #ddd'

    a.appendChild(img)
    wrap.appendChild(a)
  })
}

document.addEventListener('click', async (e) => {
  const btn = e.target.closest('.js-mark-damages')
  if (!btn) return

  e.preventDefault()
  hideDamagesAlert()

  const orderId = btn.getAttribute('data-order-id')
  if (!orderId) {
    showDamagesAlert('No se encontró el ID de la orden en el botón.', 'danger')
    return
  }

  // set en el modal
  document.getElementById('damages_order_id').value = orderId
  document.getElementById('damages_notes').value = ''
  document.getElementById('damages_photos').value = ''
  renderPhotos([])

  // abre modal
  window.$('#damagesModal').modal('show')

  // carga datos existentes
  try {
    const data = await loadDamages(orderId)
    document.getElementById('damages_notes').value = data.notes || ''
    renderPhotos(data.photos || [])
  } catch (err) {
    console.error(err)
    showDamagesAlert('No se pudieron cargar los daños existentes. Revisa consola/Network.', 'warning')
  }
})

document.getElementById('btnSaveDamages')?.addEventListener('click', async () => {
  hideDamagesAlert()

  const form = document.getElementById('damagesForm')
  const orderId = document.getElementById('damages_order_id').value
  if (!orderId) return showDamagesAlert('Falta order_id.', 'danger')

  const fd = new FormData(form)

  try {
    const res = await fetch(`/orders/${orderId}/damages`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken(),
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: fd
    })

    if (!res.ok) {
      const txt = await res.text()
      console.error(txt)
      return showDamagesAlert(`Error guardando (${res.status}). Mira consola.`, 'danger')
    }

    const data = await res.json()
    showDamagesAlert('Daños guardados correctamente.', 'success')
    renderPhotos(data.photos || [])
  } catch (err) {
    console.error(err)
    showDamagesAlert('Error de red guardando daños.', 'danger')
  }
})
