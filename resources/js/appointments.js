import { Calendar } from '@fullcalendar/core'
import esLocale from '@fullcalendar/core/locales/es'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import listPlugin from '@fullcalendar/list'
import interactionPlugin from '@fullcalendar/interaction'

// ======================
// Helpers
// ======================
function formatDateDMY(isoDate) {
  if (!isoDate) return ''
  const [y, m, d] = isoDate.split('-')
  return `${d}/${m}/${y}`
}

// Normaliza a HH:MM (acepta HH:MM:SS, 9:30, " 09:30:00 ")
function normalizeTimeHHMM(t) {
  if (!t) return ''
  const s = String(t).trim()
  const m = s.match(/^(\d{1,2}):(\d{2})/)
  if (!m) return ''
  const hh = String(m[1]).padStart(2, '0')
  const mm = String(m[2]).padStart(2, '0')
  return `${hh}:${mm}`
}

function csrfToken() {
  const meta = document.querySelector('meta[name="csrf-token"]')
  return meta ? meta.getAttribute('content') : ''
}

// Bootstrap alert en la vista
function showCalendarAlert(message, type = 'danger', timeout = 4000) {
  const alertEl = document.getElementById('calendarAlert')
  if (!alertEl) return

  alertEl.className = `alert alert-${type}`
  alertEl.textContent = message
  alertEl.classList.remove('d-none')

  if (timeout) {
    setTimeout(() => {
      alertEl.classList.add('d-none')
    }, timeout)
  }
}

function hideCalendarAlert() {
  const alertEl = document.getElementById('calendarAlert')
  if (!alertEl) return
  alertEl.classList.add('d-none')
}

// ======================
// Days status (pintar días)
// ======================
let daysMap = {} // { 'YYYY-MM-DD': {is_full,is_blocked,limit,count} }

async function fetchDaysStatus(startStr, endStr) {
  const res = await fetch(
    `/citas/days-status?start=${encodeURIComponent(startStr)}&end=${encodeURIComponent(endStr)}`,
    { headers: { Accept: 'application/json' } }
  )
  daysMap = await res.json().catch(() => ({}))
}

document.addEventListener('DOMContentLoaded', () => {
  const calendarEl = document.getElementById('calendar')
  if (!calendarEl) return

  // ======================
  // Auto refresh multiusuario
  // ======================
  const currentRange = { start: null, end: null }
  const REFRESH_MS = 20000

  // ======================
  // Modales / elementos
  // ======================
  const modal = window.$ ? window.$('#modalCita') : null
  const errorBox = document.getElementById('cita_error')
  const successBox = document.getElementById('cita_success')

  const limitModal = window.$ ? window.$('#modalLimiteDia') : null
  const limitError = document.getElementById('limit_error')
  const limitSuccess = document.getElementById('limit_success')
  const limitDate = document.getElementById('limit_date')
  const limitValue = document.getElementById('limit_value')
  const limitHint = document.getElementById('limit_hint')
  const btnRemoveOverride = document.getElementById('btnRemoveOverride')

  const btnOpenLimitModal = document.getElementById('btnOpenLimitModal')

  const detailModal = window.$ ? window.$('#modalCitaDetalle') : null
  const detailError = document.getElementById('detail_error')
  const detailSuccess = document.getElementById('detail_success')

  function hideAlert(el) {
    if (!el) return
    el.classList.add('d-none')
    el.textContent = ''
  }

  function showAlert(el, msg) {
    if (!el) return
    el.textContent = msg || ''
    el.classList.remove('d-none')
  }

  function clearLimitAlerts() {
    hideAlert(limitError)
    hideAlert(limitSuccess)
  }

  function clearCitaAlerts() {
    hideAlert(errorBox)
    hideAlert(successBox)
  }

  function clearDetailAlerts() {
    if (detailError) {
      detailError.classList.add('d-none')
      detailError.textContent = ''
    }
    if (detailSuccess) {
      detailSuccess.classList.add('d-none')
      detailSuccess.textContent = ''
    }
  }

  function showDetailError(msg) {
    if (!detailError) return
    detailError.classList.remove('d-none')
    detailError.textContent = msg || 'Error'
  }

  function showDetailSuccess(msg) {
    if (!detailSuccess) return
    detailSuccess.classList.remove('d-none')
    detailSuccess.textContent = msg || 'Ok'
  }

  // ======================
  // Pintar clases + badge CUPO LLENO
  // ======================
  function applyDayClasses() {
    const cells = calendarEl.querySelectorAll('.fc-daygrid-day[data-date]')
    cells.forEach((cell) => {
      const dateStr = cell.getAttribute('data-date')
      const st = daysMap[dateStr]

      cell.classList.remove('is-blocked', 'is-full')

      const oldBadge = cell.querySelector('.full-badge')
      if (oldBadge) oldBadge.remove()

      if (!st) return

      if (st.is_blocked) cell.classList.add('is-blocked')

      if (st.is_full) {
        cell.classList.add('is-full')

        const top = cell.querySelector('.fc-daygrid-day-top')
        if (top) {
          const badge = document.createElement('div')
          badge.className = 'full-badge'
          badge.textContent = 'CUPO LLENO'
          top.appendChild(badge)
        }
      }
    })
  }

  // Convierte refetchEvents a Promise (para esperar a que termine)
  function refetchEventsAsync(calendar) {
    return new Promise((resolve) => {
      calendar.refetchEvents()
      // FullCalendar no devuelve promise, pero con 150ms es suficiente para que repinte
      setTimeout(resolve, 150)
    })
  }

  // ======================
  // Refresh global (eventos + días)
  // ======================
  async function refreshAll({ force = false } = {}) {
    if (document.hidden && !force) return

    // si algún modal está abierto, no refrescar (evita “brinco”)
    // pero si force=true (después de guardar/eliminar/etc) sí refrescamos
    if (
      !force &&
      window.$ &&
      (window.$('#modalCita').hasClass('show') ||
        window.$('#modalCitaDetalle').hasClass('show') ||
        window.$('#modalLimiteDia').hasClass('show'))
    ) {
      return
    }

    try {
      await refetchEventsAsync(calendar)

      if (currentRange.start && currentRange.end) {
        await fetchDaysStatus(currentRange.start, currentRange.end)
        applyDayClasses()
      }
    } catch (e) {}
  }

  // ======================
  // Modal límite por día
  // ======================
  async function openDayLimitModal(dateStr) {
    clearLimitAlerts()

    if (!dateStr) {
      showAlert(limitError, 'Seleccione una fecha válida.')
      return
    }

    if (limitDate) limitDate.value = dateStr
    if (limitValue) limitValue.value = ''
    if (limitHint) limitHint.textContent = ''

    const fechaBonita = formatDateDMY(dateStr)

    try {
      const res = await fetch(`/citas/limite?date=${encodeURIComponent(dateStr)}`, {
        headers: { Accept: 'application/json' },
      })

      const data = await res.json().catch(() => ({}))
      if (!res.ok) throw new Error(data.message || 'No se pudo cargar el límite del día')

      if (data.is_override) {
        if (limitValue) limitValue.value = data.current ?? ''
        if (limitHint) limitHint.textContent = `Límite configurado para ${fechaBonita}: ${data.current}`
        if (btnRemoveOverride) btnRemoveOverride.disabled = false
      } else {
        if (limitValue) limitValue.value = ''
        if (limitHint) limitHint.textContent = `Sin límite configurado para ${fechaBonita}. Ingresa uno para guardarlo.`
        if (btnRemoveOverride) btnRemoveOverride.disabled = true
      }

      if (limitModal) limitModal.modal('show')
    } catch (err) {
      if (limitHint) limitHint.textContent = `Configuración para ${fechaBonita}`
      showAlert(limitError, err.message)
      if (limitModal) limitModal.modal('show')
    }
  }

  // ======================
  // FullCalendar
  // ======================
  const calendar = new Calendar(calendarEl, {
    plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
    locale: esLocale,
    initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
    height: 'auto',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
    },
    buttonText: {
      today: 'Hoy',
      month: 'Mes',
      week: 'Semana',
      day: 'Día',
      list: 'Lista',
    },
    navLinks: true,
    selectable: true,
    nowIndicator: true,
    dayMaxEvents: true,
    events: '/citas/events',

    // solo nombre (sin hora)
    eventDisplay: 'block',
    displayEventTime: false,

    // Captura rango visible
    datesSet: async (arg) => {
      hideCalendarAlert()
      currentRange.start = arg.startStr.slice(0, 10)
      currentRange.end = arg.endStr.slice(0, 10)

      await fetchDaysStatus(currentRange.start, currentRange.end)
      applyDayClasses()
    },

    // si ya vino => clase
    eventClassNames: (arg) => (arg.event.extendedProps.attended ? ['is-attended'] : []),

    // color por estado
    eventDidMount: (arg) => {
      const attended = arg.event.extendedProps.attended
      arg.el.style.backgroundColor = attended ? '#28a745' : '#007bff'
      arg.el.style.color = '#fff'
    },

    // click en evento => modal detalle
    eventClick: (info) => {
      const e = info.event
      const p = e.extendedProps || {}

      clearDetailAlerts()

      document.getElementById('detail_id').value = e.id
      document.getElementById('detail_name').textContent = e.title || ''
      document.getElementById('detail_phone').textContent = p.phone || ''

      const detailDateEl = document.getElementById('detail_date')
      if (detailDateEl) detailDateEl.textContent = p.date_display || ''

      const detailIsoEl = document.getElementById('detail_date_iso')
      if (detailIsoEl) detailIsoEl.value = p.date_iso || ''

      document.getElementById('detail_time').textContent = normalizeTimeHHMM(p.time || '') || ''
      document.getElementById('detail_obs').textContent = p.observations || ''

      const btnToggle = document.getElementById('btnToggleAttended')
      if (btnToggle) btnToggle.textContent = p.attended ? 'Marcar pendiente' : 'Ya vino'

      if (window.$) window.$('#modalCitaDetalle').modal('show')
    },

    // SHIFT + click => límite / click normal => cita
    dateClick: async (info) => {
      if (info.jsEvent && info.jsEvent.shiftKey) {
        await openDayLimitModal(info.dateStr)
        return
      }

      // Bloqueo si el día está lleno o bloqueado
      const st = daysMap[info.dateStr]
      if (st && (st.is_blocked || st.is_full)) {
        const msg = st.is_blocked
          ? `Día bloqueado (${formatDateDMY(info.dateStr)}). No se pueden agendar citas.`
          : `Cupo lleno (${formatDateDMY(info.dateStr)}). Límite: ${st.limit}, Agendadas: ${st.count}.`
        showCalendarAlert(msg, 'danger')
        return
      }

      hideCalendarAlert()
      clearCitaAlerts()

      // limpiar modo edición
      const form = document.getElementById('formCita')
      if (form && form.dataset.editId) delete form.dataset.editId

      document.getElementById('cita_date').value = info.dateStr
      const citaDateDisplay = document.getElementById('cita_date_display')
      if (citaDateDisplay) citaDateDisplay.textContent = `Fecha seleccionada: ${formatDateDMY(info.dateStr)}`

      document.getElementById('cita_time').value = '09:00'
      document.getElementById('cita_name').value = ''
      document.getElementById('cita_phone').value = ''
      document.getElementById('cita_obs').value = ''

      if (modal) modal.modal('show')
    },

    windowResize() {
      calendar.changeView(window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth')
    },
  })

  calendar.render()

  // Auto refresh multiusuario
  setInterval(() => refreshAll({ force: false }), REFRESH_MS)
  document.addEventListener('visibilitychange', () => {
    if (!document.hidden) refreshAll({ force: false })
  })
  window.addEventListener('focus', () => refreshAll({ force: false }))

  // ======================
  // Botón header: abrir límite HOY
  // ======================
  if (btnOpenLimitModal) {
    btnOpenLimitModal.addEventListener('click', async () => {
      const today = new Date()
      const yyyy = today.getFullYear()
      const mm = String(today.getMonth() + 1).padStart(2, '0')
      const dd = String(today.getDate()).padStart(2, '0')
      const dateStr = `${yyyy}-${mm}-${dd}`
      await openDayLimitModal(dateStr)
    })
  }

  // Click derecho para abrir límite
  calendarEl.addEventListener('contextmenu', async (e) => {
    const dayCell = e.target.closest('.fc-daygrid-day')
    if (!dayCell) return
    e.preventDefault()
    const dateStr = dayCell.getAttribute('data-date')
    if (!dateStr) return
    await openDayLimitModal(dateStr)
  })

  // ======================
  // Botones modal detalle
  // ======================
  document.getElementById('btnDeleteCita')?.addEventListener('click', async () => {
    clearDetailAlerts()
    const id = document.getElementById('detail_id').value

    try {
      const r = await fetch(`/citas/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken(), Accept: 'application/json' },
      })
      const d = await r.json().catch(() => ({}))
      if (!r.ok) throw new Error(d.message || 'No se pudo eliminar')

      showDetailSuccess(d.message || 'Eliminado')

      // refresco completo inmediato
      await refreshAll({ force: true })

      setTimeout(() => (detailModal ? detailModal.modal('hide') : null), 300)
    } catch (err) {
      showDetailError(err.message)
    }
  })

  document.getElementById('btnToggleAttended')?.addEventListener('click', async () => {
    clearDetailAlerts()
    const id = document.getElementById('detail_id').value

    try {
      const r = await fetch(`/citas/${id}/attended`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': csrfToken(), Accept: 'application/json' },
      })
      const d = await r.json().catch(() => ({}))
      if (!r.ok) throw new Error(d.message || 'No se pudo actualizar')

      showDetailSuccess(d.message || 'Actualizado')

      // refresco completo inmediato
      await refreshAll({ force: true })

      setTimeout(() => (detailModal ? detailModal.modal('hide') : null), 250)
    } catch (err) {
      showDetailError(err.message)
    }
  })

  // Editar: cargar datos al modal de cita
  document.getElementById('btnEditCita')?.addEventListener('click', () => {
    const id = document.getElementById('detail_id').value
    const name = document.getElementById('detail_name').textContent
    const phone = document.getElementById('detail_phone').textContent
    const timeTxt = document.getElementById('detail_time').textContent
    const obs = document.getElementById('detail_obs').textContent
    const iso = document.getElementById('detail_date_iso')?.value || ''

    const form = document.getElementById('formCita')
    if (form) form.dataset.editId = id

    document.getElementById('cita_date').value = iso
    const citaDateDisplay = document.getElementById('cita_date_display')
    if (citaDateDisplay) citaDateDisplay.textContent = iso ? `Fecha seleccionada: ${formatDateDMY(iso)}` : ''

    document.getElementById('cita_time').value = normalizeTimeHHMM(timeTxt) || '09:00'
    document.getElementById('cita_name').value = name || ''
    document.getElementById('cita_phone').value = phone || ''
    document.getElementById('cita_obs').value = obs || ''

    if (detailModal) detailModal.modal('hide')
    if (window.$) window.$('#modalCita').modal('show')
  })

  // ======================
  // Guardar cita (crear o editar)
  // ======================
  const formCita = document.getElementById('formCita')
  if (formCita) {
    formCita.addEventListener('submit', async (e) => {
      e.preventDefault()
      clearCitaAlerts()

      // asegurar HH:MM
      const timeInput = document.getElementById('cita_time')
      if (timeInput) timeInput.value = normalizeTimeHHMM(timeInput.value) || timeInput.value

      const editId = e.target.dataset.editId
      const url = editId ? `/citas/${editId}` : '/citas'
      const formData = new FormData(e.target)

      // Laravel: editar con FormData => POST + _method=PUT
      if (editId) formData.append('_method', 'PUT')

      try {
        const r = await fetch(url, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken(),
            Accept: 'application/json',
          },
          body: formData,
        })

        const d = await r.json().catch(() => ({}))
        if (!r.ok) {
          if (d.errors) {
            const firstKey = Object.keys(d.errors)[0]
            throw new Error(d.errors[firstKey][0])
          }
          throw new Error(d.message || 'Error')
        }

        showAlert(successBox, d.message || 'Guardado')

        // refresco completo inmediato
        await refreshAll({ force: true })

        // limpiar modo edición
        if (e.target.dataset.editId) delete e.target.dataset.editId

        setTimeout(() => (modal ? modal.modal('hide') : null), 450)
      } catch (err) {
        showAlert(errorBox, err.message)
      }
    })
  }

  // ======================
  // Guardar límite del día
  // ======================
  const formLimite = document.getElementById('formLimiteDia')
  if (formLimite) {
    formLimite.addEventListener('submit', async (e) => {
      e.preventDefault()
      clearLimitAlerts()

      const dateStr = limitDate ? limitDate.value : ''
      const maxVal = limitValue ? limitValue.value : ''
      if (!dateStr) return showAlert(limitError, 'Selecciona una fecha.')
      if (maxVal === '' || isNaN(Number(maxVal))) return showAlert(limitError, 'Ingresa un número válido.')

      const payload = new FormData()
      payload.append('date', dateStr)
      payload.append('max_per_day', String(maxVal))

      try {
        const r = await fetch('/citas/limite', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken(),
            Accept: 'application/json',
          },
          body: payload,
        })

        const d = await r.json().catch(() => ({}))
        if (!r.ok) {
          if (d.errors) {
            const firstKey = Object.keys(d.errors)[0]
            throw new Error(d.errors[firstKey][0])
          }
          throw new Error(d.message || 'No se pudo guardar el límite')
        }

        showAlert(limitSuccess, d.message || 'Límite guardado')
        if (btnRemoveOverride) btnRemoveOverride.disabled = false

        // refresco completo inmediato
        await refreshAll({ force: true })
      } catch (err) {
        showAlert(limitError, err.message)
      }
    })
  }

  // ======================
  // Quitar override del día
  // ======================
  if (btnRemoveOverride) {
    btnRemoveOverride.addEventListener('click', async () => {
      clearLimitAlerts()

      try {
        const dateStr = limitDate ? limitDate.value : ''
        if (!dateStr) return showAlert(limitError, 'Selecciona una fecha.')

        const r = await fetch('/citas/limite?date=' + encodeURIComponent(dateStr), {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': csrfToken(),
            Accept: 'application/json',
          },
        })

        const d = await r.json().catch(() => ({}))
        if (!r.ok) throw new Error(d.message || 'No se pudo quitar el límite')

        showAlert(limitSuccess, d.message || 'Límite eliminado')
        if (btnRemoveOverride) btnRemoveOverride.disabled = true
        if (limitValue) limitValue.value = ''
        if (limitHint) limitHint.textContent = 'Este día NO tiene límite configurado. Ingresa uno para guardarlo.'

        // refresco completo inmediato
        await refreshAll({ force: true })
      } catch (err) {
        showAlert(limitError, err.message)
      }
    })
  }
})
