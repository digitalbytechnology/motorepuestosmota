<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderDamageController extends Controller
{
    public function show(Order $order)
    {
        // Ajusta nombres/relaciones si los tuyos difieren
        $inspection = $order->inspection; // o $order->orderInspection
        if (!$inspection) {
            return response()->json([
                'notes' => '',
                'photos' => [],
            ]);
        }

        $photos = $inspection->photos()->latest()->get()->map(fn($p) => [
            'id' => $p->id,
            'url' => Storage::url($p->path),
        ]);

        return response()->json([
            'notes' => $inspection->notes ?? '',
            'photos' => $photos,
        ]);
    }

    public function store(Request $request, Order $order)
    {
        $data = $request->validate([
            'notes' => ['nullable','string','max:2000'],
            'photos.*' => ['nullable','image','max:5120'], // 5MB c/u
        ]);

        $inspection = $order->inspection()->firstOrCreate([]); // ajusta si tu relación es distinta
        $inspection->notes = $data['notes'] ?? $inspection->notes;
        $inspection->save();

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store("orders/{$order->id}/damages", 'public');

                $inspection->photos()->create([
                    'path' => $path,
                ]);
            }
        }

        $photos = $inspection->photos()->latest()->get()->map(fn($p) => [
            'id' => $p->id,
            'url' => Storage::url($p->path),
        ]);

        return response()->json([
            'ok' => true,
            'photos' => $photos,
        ]);
    }
}
