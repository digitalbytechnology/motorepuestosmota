<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\PartCategory;
use Illuminate\Http\Request;

class PartController extends Controller
{
    public function index()
    {
        $parts = Part::with('category')->latest()->get();
        return view('parts.index', compact('parts'));
    }

    public function create()
    {
        $categories = PartCategory::where('activo', true)->orderBy('nombre')->get();
        return view('parts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sku' => ['nullable','string','max:50','unique:parts,sku'],
            'nombre' => ['required','string','max:150'],
            'descripcion' => ['nullable','string'],
            'aplica_a' => ['nullable','string'],
            'category_id' => ['nullable','exists:part_categories,id'],
            'marca' => ['nullable','string','max:80'],
            'costo' => ['required','numeric','min:0'],
            'precio' => ['required','numeric','min:0'],
            'stock_min' => ['required','integer','min:0'],
        ]);

        Part::create($data);

        return redirect()->route('parts.index')->with('success','Repuesto creado.');
    }

    public function edit(Part $part)
    {
        $categories = PartCategory::orderBy('nombre')->get();
        return view('parts.edit', compact('part','categories'));
    }

    public function update(Request $request, Part $part)
    {
        $data = $request->validate([
            'sku' => ['nullable','string','max:50','unique:parts,sku,'.$part->id],
            'nombre' => ['required','string','max:150'],
            'descripcion' => ['nullable','string'],
            'aplica_a' => ['nullable','string'],
            'category_id' => ['nullable','exists:part_categories,id'],
            'marca' => ['nullable','string','max:80'],
            'costo' => ['required','numeric','min:0'],
            'precio' => ['required','numeric','min:0'],
            'stock_min' => ['required','integer','min:0'],
            'activo' => ['required','in:0,1'],
        ]);

        $part->update($data);

        return redirect()->route('parts.index')->with('success','Repuesto actualizado.');
    }

    public function destroy(Part $part)
    {
        $part->delete();
        return redirect()->route('parts.index')->with('success','Repuesto eliminado.');
    }
}
