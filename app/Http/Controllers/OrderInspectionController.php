<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderInspection;
use App\Models\OrderInspectionPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderInspectionController extends Controller
{
    public function edit(Order $order)
    {
        $inspection = OrderInspection::firstOrCreate(
            ['order_id' => $order->id],
            ['created_by' => auth()->id()]
        );

        $inspection->load('photos');

        return view('orders.inspection.edit', compact('order','inspection'));
    }

    public function update(Request $request, Order $order)
    {
        $inspection = OrderInspection::firstOrCreate(['order_id' => $order->id]);

        $data = $request->validate([
            // checklist (nullable boolean para permitir N/A)
            'luz_silvin_bajas' => ['nullable','in:0,1'],
            'luz_silvin_altas' => ['nullable','in:0,1'],
            'luz_stop' => ['nullable','in:0,1'],
            'pidevias' => ['nullable','in:0,1'],
            'bocina' => ['nullable','in:0,1'],
            'neblineras' => ['nullable','in:0,1'],
            'alarma' => ['nullable','in:0,1'],

            'aceite_pct' => ['nullable','in:0,25,50,75,100'],
            'gasolina_level' => ['nullable','in:E,Q1,H,Q3,F'],
            'arranca' => ['nullable','in:0,1'],

            'check_engine' => ['nullable','in:0,1'],
            'check_engine_detalle' => ['nullable','string','max:120'],
            'odometro' => ['nullable','integer','min:0','max:2000000'],

            'frenos' => ['nullable','in:ok,regular,malo'],
            'llantas' => ['nullable','in:ok,gastadas'],
            'llantas_profundidad' => ['nullable','string','max:30'],
            'espejos' => ['nullable','in:0,1'],

            'doc_tarjeta' => ['nullable','in:0,1'],
            'doc_copia_llave' => ['nullable','in:0,1'],
            'accesorios_recibidos' => ['nullable','string'],

            'observaciones' => ['nullable','string'],
        ]);

        $inspection->fill($data);
        $inspection->created_by = $inspection->created_by ?: auth()->id();
        $inspection->save();

        return back()->with('success','Inspección actualizada.');
    }

    public function storePhotos(Request $request, Order $order)
    {
        $inspection = OrderInspection::firstOrCreate(['order_id' => $order->id]);

        $request->validate([
            'photos' => ['required','array','min:1'],
            'photos.*' => ['file','mimes:jpg,jpeg,png,webp','max:6144'], // 6MB c/u
        ]);

        foreach ($request->file('photos') as $file) {
            $path = $file->store("public/inspections/orders/{$order->id}");
            OrderInspectionPhoto::create([
                'order_inspection_id' => $inspection->id,
                'path' => $path,
                'annotations' => [],
            ]);
        }

        return back()->with('success','Fotos subidas.');
    }

    public function destroyPhoto(Order $order, OrderInspectionPhoto $photo)
    {
        // seguridad mínima: validar que pertenece a la inspección de esa orden
        if ($photo->inspection->order_id !== $order->id) abort(403);

        Storage::delete($photo->path);
        if ($photo->flattened_path) Storage::delete($photo->flattened_path);

        $photo->delete();
        return back()->with('success','Foto eliminada.');
    }

    public function saveAnnotations(Request $request, Order $order, OrderInspectionPhoto $photo)
    {
        if ($photo->inspection->order_id !== $order->id) abort(403);

        $data = $request->validate([
            'annotations' => ['nullable','array'],
        ]);

        $photo->annotations = $data['annotations'] ?? [];
        $photo->save();

        return response()->json(['ok' => true]);
    }

    public function saveSignature(Request $request, Order $order)
    {
        $inspection = OrderInspection::firstOrCreate(['order_id' => $order->id]);

        $data = $request->validate([
            'signature' => ['required','string'], // data:image/png;base64,...
        ]);

        $base64 = $data['signature'];
        if (!str_starts_with($base64, 'data:image/')) {
            return response()->json(['ok' => false, 'msg' => 'Firma inválida'], 422);
        }

        [$meta, $content] = explode(',', $base64, 2);
        $bin = base64_decode($content);

        $path = "public/inspections/orders/{$order->id}/signature.png";
        Storage::put($path, $bin);

        $inspection->firma_path = $path;
        $inspection->save();

        return response()->json(['ok' => true]);
    }
}
