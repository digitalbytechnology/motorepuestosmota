<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use App\Models\Part;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryMovementController extends Controller
{
    public function create(Part $part)
    {
        return view('inventory.create', compact('part'));
    }

    public function store(Request $request, Part $part)
    {
        $data = $request->validate([
            'type' => ['required','in:in,out,adjust'],
            'qty' => ['required','integer','min:1'],
            'unit_cost' => ['nullable','numeric','min:0'],
            'note' => ['nullable','string','max:255'],
        ]);

        DB::transaction(function () use ($data, $part) {
            $qtySigned = match ($data['type']) {
                'in' => +$data['qty'],
                'out' => -$data['qty'],
                'adjust' => +$data['qty'], // ajuste suma (si quieres ajuste negativo luego lo hacemos)
            };

            if ($qtySigned < 0 && ($part->stock + $qtySigned) < 0) {
                abort(422, 'Stock insuficiente.');
            }

            InventoryMovement::create([
                'part_id' => $part->id,
                'type' => $data['type'],
                'qty' => $qtySigned,
                'unit_cost' => $data['unit_cost'] ?? null,
                'note' => $data['note'] ?? null,
                'user_id' => auth()->id(),
            ]);

            $part->update([
                'stock' => $part->stock + $qtySigned,
                'costo' => ($data['type'] === 'in' && isset($data['unit_cost']))
                    ? $data['unit_cost']
                    : $part->costo,
            ]);
        });

        return redirect()->route('parts.index')->with('success','Movimiento aplicado.');
    }

    public function kardex(Part $part)
    {
        $movements = $part->movements()->latest()->get();
        return view('inventory.kardex', compact('part','movements'));
    }
}
