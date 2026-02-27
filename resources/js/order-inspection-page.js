import Konva from 'konva'

function csrfToken() {
  // usa meta o window.__INSPECTION.csrf
  return (
    window.__INSPECTION?.csrf ||
    document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
    ''
  )
}

function patchUrl(template, photoId) {
  return (template || '').replace('999999', String(photoId))
}

/**
 * Modal helper: Bootstrap 4 (jQuery) o Bootstrap 5
 */
function showModalById(id) {
  const el = document.getElementById(id)
  if (!el) return console.error('No existe modal:', id)

  if (window.$ && window.$.fn && typeof window.$.fn.modal === 'function') {
    window.$(el).modal('show')
    return
  }

  if (window.bootstrap && window.bootstrap.Modal) {
    window.bootstrap.Modal.getOrCreateInstance(el).show()
    return
  }

  console.error('No hay modal disponible (ni BS4 ni BS5).')
}

function hideModalById(id) {
  const el = document.getElementById(id)
  if (!el) return

  if (window.$ && window.$.fn && typeof window.$.fn.modal === 'function') {
    window.$(el).modal('hide')
    return
  }

  if (window.bootstrap && window.bootstrap.Modal) {
    window.bootstrap.Modal.getOrCreateInstance(el).hide()
  }
}

document.addEventListener('DOMContentLoaded', () => {
  // bandera para verificar que el archivo cargó
  window.__ORDER_INSPECTION_LOADED = true
  console.log('[order-inspection] loaded ')

  // Solo corre en la página de inspección
  const konvaContainer = document.getElementById('konvaContainer')
  const signaturePad = document.getElementById('signaturePad')
  if (!konvaContainer && !signaturePad) return

  //  toma URLs desde window.__INSPECTION (Blade)
  const saveAnnTemplate = window.__INSPECTION?.saveAnnotationsUrlTemplate || ''
  const saveSignatureUrl = window.__INSPECTION?.saveSignatureUrl || ''

  // ---------- Annotator (Konva) ----------
  const container = konvaContainer
  if (container) {
    let stage, layer, bgImage
    let currentPhotoId = null
    let annotations = []

    const damageTypeEl = document.getElementById('damageType')
    const toolTypeEl = document.getElementById('toolType')
    const noteEl = document.getElementById('damageNote')

    function initStage(imgUrl, initialAnnotations) {
      container.innerHTML = ''
      annotations = Array.isArray(initialAnnotations) ? initialAnnotations : []

      const width = container.clientWidth || 900
      const height = 520

      stage = new Konva.Stage({ container: 'konvaContainer', width, height })
      layer = new Konva.Layer()
      stage.add(layer)

      const imageObj = new window.Image()
      imageObj.onload = () => {
        const scale = Math.min(width / imageObj.width, height / imageObj.height)
        const imgW = imageObj.width * scale
        const imgH = imageObj.height * scale

        bgImage = new Konva.Image({
          image: imageObj,
          x: (width - imgW) / 2,
          y: (height - imgH) / 2,
          width: imgW,
          height: imgH,
        })

        layer.add(bgImage)
        layer.draw()

        annotations.forEach(a => drawAnnotation(a, false))
        layer.draw()
      }
      imageObj.src = imgUrl
    }

    function drawAnnotation(a, push = true) {
      const type = a.type
      const shape = a.shape
      const note = a.note || ''
      let node = null

      if (shape === 'rect') {
        node = new Konva.Rect({ x: a.x, y: a.y, width: a.w, height: a.h, stroke: 'red', strokeWidth: 3 })
      } else if (shape === 'circle') {
        node = new Konva.Circle({ x: a.x, y: a.y, radius: a.r, stroke: 'red', strokeWidth: 3 })
      } else if (shape === 'arrow') {
        node = new Konva.Arrow({ points: a.points, stroke: 'red', fill: 'red', strokeWidth: 3, pointerLength: 10, pointerWidth: 10 })
      } else if (shape === 'text') {
        node = new Konva.Text({ x: a.x, y: a.y, text: a.text || 'Texto', fill: 'red', fontSize: 20, fontStyle: 'bold' })
      }

      if (!node) return
      node.setAttr('meta', { type, shape, note })

      node.on('dblclick dbltap', () => {
        node.destroy()
        layer.draw()
      })

      layer.add(node)
      if (push) annotations.push(a)
    }

    let drawing = false
    let startX = 0
    let startY = 0
    let tempNode = null

    function bindDraw() {
      if (!stage) return

      stage.off('mousedown touchstart')
      stage.off('mousemove touchmove')
      stage.off('mouseup touchend')

      stage.on('mousedown touchstart', () => {
        drawing = true
        const pos = stage.getPointerPosition()
        if (!pos) return
        startX = pos.x
        startY = pos.y

        const tool = toolTypeEl?.value || 'rect'
        if (tool === 'rect') {
          tempNode = new Konva.Rect({ x: startX, y: startY, width: 1, height: 1, stroke: 'red', strokeWidth: 3 })
        } else if (tool === 'circle') {
          tempNode = new Konva.Circle({ x: startX, y: startY, radius: 1, stroke: 'red', strokeWidth: 3 })
        } else if (tool === 'arrow') {
          tempNode = new Konva.Arrow({ points: [startX, startY, startX + 1, startY + 1], stroke: 'red', fill: 'red', strokeWidth: 3 })
        } else if (tool === 'text') {
          drawing = false
          const text = prompt('Texto:', 'Rayón')
          if (!text) return
          drawAnnotation({ type: damageTypeEl?.value || 'otro', shape: 'text', x: startX, y: startY, text, note: noteEl?.value || '' }, true)
          layer.draw()
          return
        }

        if (tempNode) {
          layer.add(tempNode)
          layer.draw()
        }
      })

      stage.on('mousemove touchmove', () => {
        if (!drawing || !tempNode) return
        const pos = stage.getPointerPosition()
        if (!pos) return

        const tool = toolTypeEl?.value || 'rect'
        if (tool === 'rect') {
          tempNode.width(pos.x - startX)
          tempNode.height(pos.y - startY)
        } else if (tool === 'circle') {
          const r = Math.sqrt((pos.x - startX) ** 2 + (pos.y - startY) ** 2)
          tempNode.radius(Math.max(1, r))
        } else if (tool === 'arrow') {
          tempNode.points([startX, startY, pos.x, pos.y])
        }
        layer.draw()
      })

      stage.on('mouseup touchend', () => {
        if (!drawing || !tempNode) return
        drawing = false

        const tool = toolTypeEl?.value || 'rect'
        const note = noteEl?.value || ''
        const type = damageTypeEl?.value || 'otro'

        let a = null
        if (tool === 'rect') {
          a = { type, shape: 'rect', x: tempNode.x(), y: tempNode.y(), w: tempNode.width(), h: tempNode.height(), note }
        } else if (tool === 'circle') {
          a = { type, shape: 'circle', x: tempNode.x(), y: tempNode.y(), r: tempNode.radius(), note }
        } else if (tool === 'arrow') {
          a = { type, shape: 'arrow', points: tempNode.points(), note }
        }

        tempNode.destroy()
        tempNode = null

        if (a) drawAnnotation(a, true)
        layer.draw()
      })
    }

    //  click delegación
    document.addEventListener('click', (e) => {
      const btn = e.target.closest('.btn-open-annotator')
      if (!btn) return

      currentPhotoId = btn.dataset.photoId
      const imgUrl = btn.dataset.photoUrl
      const ann = JSON.parse(btn.dataset.annotations || '[]')

      console.log('[order-inspection] click marcar daños ', currentPhotoId)

      showModalById('annotatorModal')

      setTimeout(() => {
        initStage(imgUrl, ann)
        bindDraw()
      }, 250)
    })

    document.getElementById('btnClearAnnotations')?.addEventListener('click', (e) => {
      e.preventDefault()
      if (!layer) return
      layer.getChildren().forEach((n) => {
        if (n !== bgImage) n.destroy()
      })
      annotations = []
      layer.draw()
    })

    document.getElementById('btnSaveAnnotations')?.addEventListener('click', async (e) => {
      e.preventDefault()
      if (!currentPhotoId || !stage || !layer || !bgImage) return

      const out = []
      layer.getChildren().forEach(n => {
        if (n === bgImage) return
        const m = n.getAttr('meta') || {}
        const note = m.note || ''

        if (n.className === 'Rect')   out.push({ type: m.type || (damageTypeEl?.value || 'otro'), shape: 'rect', x: n.x(), y: n.y(), w: n.width(), h: n.height(), note })
        if (n.className === 'Circle') out.push({ type: m.type || (damageTypeEl?.value || 'otro'), shape: 'circle', x: n.x(), y: n.y(), r: n.radius(), note })
        if (n.className === 'Arrow')  out.push({ type: m.type || (damageTypeEl?.value || 'otro'), shape: 'arrow', points: n.points(), note })
        if (n.className === 'Text')   out.push({ type: m.type || (damageTypeEl?.value || 'otro'), shape: 'text', x: n.x(), y: n.y(), text: n.text(), note })
      })

      const previewDataUrl = stage.toDataURL({ pixelRatio: 2 })
      const url = patchUrl(saveAnnTemplate, currentPhotoId)

      if (!url) {
        alert('No existe saveAnnotationsUrlTemplate en window.__INSPECTION')
        return
      }

      const res = await fetch(url, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken(),
          'Accept': 'application/json'
        },
        body: JSON.stringify({ annotations: out, preview_data_url: previewDataUrl })
      })

      if (!res.ok) {
        console.error(await res.text())
        alert('Error guardando marcas')
        return
      }

      const data = await res.json()

      // actualiza miniatura sin recargar
      const img = document.querySelector(`button[data-photo-id="${currentPhotoId}"]`)
        ?.closest('.card')
        ?.querySelector('img')

      if (img && data.preview_url) img.src = data.preview_url + `?t=${Date.now()}`

      hideModalById('annotatorModal')
    })
  }

  // ---------- Signature Pad ----------
  const canvas = signaturePad
  if (canvas) {
    const ctx = canvas.getContext('2d')
    let drawing = false

    function pos(e) {
      const rect = canvas.getBoundingClientRect()
      const touch = e.touches?.[0]
      const clientX = touch ? touch.clientX : e.clientX
      const clientY = touch ? touch.clientY : e.clientY
      return { x: clientX - rect.left, y: clientY - rect.top }
    }

    function start(e) {
      drawing = true
      const p = pos(e)
      ctx.beginPath()
      ctx.moveTo(p.x, p.y)
    }

    function move(e) {
      if (!drawing) return
      e.preventDefault()
      const p = pos(e)
      ctx.lineTo(p.x, p.y)
      ctx.strokeStyle = '#111'
      ctx.lineWidth = 2
      ctx.stroke()
    }

    function end() { drawing = false }

    canvas.addEventListener('mousedown', start)
    canvas.addEventListener('mousemove', move)
    canvas.addEventListener('mouseup', end)
    canvas.addEventListener('mouseleave', end)

    canvas.addEventListener('touchstart', start, { passive: false })
    canvas.addEventListener('touchmove', move, { passive: false })
    canvas.addEventListener('touchend', end)

    document.getElementById('sigClear')?.addEventListener('click', (e) => {
      e.preventDefault()
      ctx.clearRect(0, 0, canvas.width, canvas.height)
    })

    document.getElementById('sigSave')?.addEventListener('click', async (e) => {
      e.preventDefault()
      const dataUrl = canvas.toDataURL('image/png')

      if (!saveSignatureUrl) {
        alert('No existe saveSignatureUrl en window.__INSPECTION')
        return
      }

      const res = await fetch(saveSignatureUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken(),
          'Accept': 'application/json'
        },
        body: JSON.stringify({ signature: dataUrl })
      })

      if (!res.ok) {
        console.error(await res.text())
        alert('Error guardando firma')
        return
      }

      alert('Firma guardada')
      window.location.reload()
    })
  }
})
