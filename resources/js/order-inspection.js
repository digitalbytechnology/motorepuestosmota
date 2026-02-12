import Konva from 'konva'

function patchUrl(template, photoId) {
  return template.replace('999999', String(photoId))
}

document.addEventListener('DOMContentLoaded', () => {
  // ---------- Annotator (Konva) ----------
  const modal = document.getElementById('annotatorModal')
  const container = document.getElementById('konvaContainer')
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

      const width = container.clientWidth
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

        // Render saved annotations
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
        node = new Konva.Rect({
          x: a.x, y: a.y, width: a.w, height: a.h,
          stroke: 'red', strokeWidth: 3
        })
      } else if (shape === 'circle') {
        node = new Konva.Circle({
          x: a.x, y: a.y, radius: a.r,
          stroke: 'red', strokeWidth: 3
        })
      } else if (shape === 'arrow') {
        node = new Konva.Arrow({
          points: a.points,
          stroke: 'red', fill: 'red', strokeWidth: 3, pointerLength: 10, pointerWidth: 10
        })
      } else if (shape === 'text') {
        node = new Konva.Text({
          x: a.x, y: a.y, text: a.text || 'Texto',
          fill: 'red', fontSize: 20, fontStyle: 'bold'
        })
      }

      if (!node) return

      node.setAttr('meta', { type, shape, note })
      node.on('dblclick dbltap', () => {
        // eliminar rápido
        node.destroy()
        layer.draw()
        // rebuild annotations later on save
      })

      layer.add(node)
      if (push) annotations.push(a)
    }

    // Simple draw interaction
    let drawing = false
    let startX = 0
    let startY = 0
    let tempNode = null

    stage?.on?.('mousedown', () => {}) // noop; stage not ready at load

    function bindDraw() {
      stage.off('mousedown')
      stage.off('mousemove')
      stage.off('mouseup')

      stage.on('mousedown touchstart', () => {
        drawing = true
        const pos = stage.getPointerPosition()
        startX = pos.x
        startY = pos.y

        const tool = toolTypeEl.value
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
          const a = { type: damageTypeEl.value, shape: 'text', x: startX, y: startY, text, note: noteEl.value || '' }
          drawAnnotation(a, true)
          layer.draw()
          return
        }

        layer.add(tempNode)
        layer.draw()
      })

      stage.on('mousemove touchmove', () => {
        if (!drawing || !tempNode) return
        const pos = stage.getPointerPosition()
        const tool = toolTypeEl.value

        if (tool === 'rect') {
          tempNode.width(pos.x - startX)
          tempNode.height(pos.y - startY)
        } else if (tool === 'circle') {
          const r = Math.sqrt(Math.pow(pos.x - startX, 2) + Math.pow(pos.y - startY, 2))
          tempNode.radius(Math.max(1, r))
        } else if (tool === 'arrow') {
          tempNode.points([startX, startY, pos.x, pos.y])
        }
        layer.draw()
      })

      stage.on('mouseup touchend', () => {
        if (!drawing || !tempNode) return
        drawing = false

        const tool = toolTypeEl.value
        const pos = stage.getPointerPosition()

        let a = null
        if (tool === 'rect') {
          a = { type: damageTypeEl.value, shape: 'rect', x: tempNode.x(), y: tempNode.y(), w: tempNode.width(), h: tempNode.height(), note: noteEl.value || '' }
        } else if (tool === 'circle') {
          a = { type: damageTypeEl.value, shape: 'circle', x: tempNode.x(), y: tempNode.y(), r: tempNode.radius(), note: noteEl.value || '' }
        } else if (tool === 'arrow') {
          a = { type: damageTypeEl.value, shape: 'arrow', points: tempNode.points(), note: noteEl.value || '' }
        }

        tempNode.destroy()
        tempNode = null

        if (a) drawAnnotation(a, true)
        layer.draw()
      })
    }

    // Open modal button
    document.querySelectorAll('.btn-open-annotator').forEach(btn => {
      btn.addEventListener('click', () => {
        currentPhotoId = btn.dataset.photoId
        const url = btn.dataset.photoUrl
        const ann = JSON.parse(btn.dataset.annotations || '[]')

        $('#annotatorModal').modal('show')

        setTimeout(() => {
          initStage(url, ann)
          bindDraw()
        }, 250)
      })
    })

    toolTypeEl?.addEventListener('change', () => {
      if (stage) bindDraw()
    })

    document.getElementById('btnClearAnnotations')?.addEventListener('click', () => {
      if (!layer) return
      // remove all except background
      layer.getChildren().forEach((n, idx) => {
        if (n !== bgImage) n.destroy()
      })
      annotations = []
      layer.draw()
    })

    document.getElementById('btnSaveAnnotations')?.addEventListener('click', async () => {
      if (!currentPhotoId) return

      // Build annotations from layer shapes (excluding bg)
      const out = []
      layer.getChildren().forEach(n => {
        if (n === bgImage) return
        if (n.className === 'Rect') out.push({ type: damageTypeEl.value, shape: 'rect', x: n.x(), y: n.y(), w: n.width(), h: n.height(), note: n.getAttr('meta')?.note || '' })
        if (n.className === 'Circle') out.push({ type: damageTypeEl.value, shape: 'circle', x: n.x(), y: n.y(), r: n.radius(), note: n.getAttr('meta')?.note || '' })
        if (n.className === 'Arrow') out.push({ type: damageTypeEl.value, shape: 'arrow', points: n.points(), note: n.getAttr('meta')?.note || '' })
        if (n.className === 'Text') out.push({ type: damageTypeEl.value, shape: 'text', x: n.x(), y: n.y(), text: n.text(), note: n.getAttr('meta')?.note || '' })
      })

      const url = patchUrl(window.__INSPECTION.saveAnnotationsUrlTemplate, currentPhotoId)

      const res = await fetch(url, {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': window.__INSPECTION.csrf,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ annotations: out })
      })

      if (!res.ok) {
        alert('Error guardando marcas')
        return
      }
      alert('Marcas guardadas ')
      $('#annotatorModal').modal('hide')
      window.location.reload()
    })
  }

  // ---------- Signature Pad (simple canvas) ----------
  const canvas = document.getElementById('signaturePad')
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

    function start(e) { drawing = true; const p = pos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y); }
    function move(e) { if (!drawing) return; e.preventDefault(); const p = pos(e); ctx.lineTo(p.x, p.y); ctx.strokeStyle = '#111'; ctx.lineWidth = 2; ctx.stroke(); }
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

      const res = await fetch(window.__INSPECTION.saveSignatureUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': window.__INSPECTION.csrf,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ signature: dataUrl })
      })

      if (!res.ok) {
        alert('Error guardando firma')
        return
      }

      alert('Firma guardada ')
      window.location.reload()
    })
  }
})
