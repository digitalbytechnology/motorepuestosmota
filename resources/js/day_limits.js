function csrfToken() {
  const meta = document.querySelector('meta[name="csrf-token"]')
  return meta ? meta.getAttribute('content') : ''
}

function $(id) {
  return document.getElementById(id)
}

function showOk(msg) {
  $('okMsg').classList.remove('d-none')
  $('okMsg').innerText = msg

  $('errMsg').classList.add('d-none')
  $('errMsg').innerText = ''
}

function showErr(msg) {
  $('errMsg').classList.remove('d-none')
  $('errMsg').innerText = msg

  $('okMsg').classList.add('d-none')
  $('okMsg').innerText = ''
}

function clearMsgs() {
  $('okMsg').classList.add('d-none')
  $('okMsg').innerText = ''
  $('errMsg').classList.add('d-none')
  $('errMsg').innerText = ''
}

async function loadLimit(dateStr) {
  clearMsgs()
  if (!dateStr) return

  const r = await fetch(`/citas/limite?date=${encodeURIComponent(dateStr)}`, {
    headers: { 'Accept': 'application/json' },
  })

  const d = await r.json().catch(() => ({}))
  if (!r.ok) {
    throw new Error(d.message || 'No se pudo cargar el límite')
  }

  $('max_per_day').value = d.current
  $('hint').innerText = d.is_override
    ? `Este día tiene límite especial. Default: ${d.default}`
    : `Este día usa el límite por defecto: ${d.default}`

  $('btnDelete').disabled = !d.is_override
}

async function saveLimit(dateStr, maxPerDay) {
  clearMsgs()

  const payload = new FormData()
  payload.append('date', dateStr)
  payload.append('max_per_day', String(maxPerDay))

  const r = await fetch('/citas/limite', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': csrfToken(),
      'Accept': 'application/json',
    },
    body: payload,
  })

  const d = await r.json().catch(() => ({}))
  if (!r.ok) {
    // validación laravel
    if (d.errors) {
      const firstKey = Object.keys(d.errors)[0]
      throw new Error(d.errors[firstKey][0])
    }
    throw new Error(d.message || 'No se pudo guardar')
  }

  showOk(d.message || 'Guardado')
  await loadLimit(dateStr)
}

async function deleteOverride(dateStr) {
  clearMsgs()

  const r = await fetch(`/citas/limite?date=${encodeURIComponent(dateStr)}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': csrfToken(),
      'Accept': 'application/json',
    },
  })

  const d = await r.json().catch(() => ({}))
  if (!r.ok) throw new Error(d.message || 'No se pudo eliminar')

  showOk(d.message || 'Eliminado')
  await loadLimit(dateStr)
}

document.addEventListener('DOMContentLoaded', async () => {
  // si esta página no es la de límites, no hace nada
  if (!$('formDayLimit')) return

  // Cuando cambias fecha, carga el límite (independiente)
  $('date').addEventListener('change', async (e) => {
    try {
      await loadLimit(e.target.value)
    } catch (err) {
      showErr(err.message)
    }
  })

  // Guardar
  $('formDayLimit').addEventListener('submit', async (e) => {
    e.preventDefault()
    const dateStr = $('date').value
    const max = $('max_per_day').value

    try {
      await saveLimit(dateStr, max)
    } catch (err) {
      showErr(err.message)
    }
  })

  // Quitar override
  $('btnDelete').addEventListener('click', async () => {
    const dateStr = $('date').value
    if (!dateStr) return

    try {
      await deleteOverride(dateStr)
    } catch (err) {
      showErr(err.message)
    }
  })
})
