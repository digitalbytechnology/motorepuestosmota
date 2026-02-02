<?php
namespace App\Http\Controllers;

use App\Models\Labor;
use Illuminate\Http\Request;

class LaborController extends Controller
{
    public function index()
    {
        $labors = Labor::latest()->get();
        return view('labors.index', compact('labors'));
    }

    public function create()
    {
        return view('labors.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required','string','max:100'],
            'descripcion' => ['nullable','string'],
            'precio' => ['required','numeric','min:0'],
        ]);

        Labor::create($data);

        return redirect()->route('labors.index')
            ->with('success', 'Servicio creado.');
    }

    public function edit(Labor $labor)
    {
        return view('labors.edit', compact('labor'));
    }

    public function update(Request $request, Labor $labor)
{
    $data = $request->validate([
        'nombre' => ['required','string','max:100'],
        'descripcion' => ['nullable','string'],
        'precio' => ['required','numeric','min:0'],
        'activo' => ['required','in:0,1'], 
    ]);

    $labor->update($data);

    return redirect()->route('labors.index')
        ->with('success', 'Servicio actualizado.');
}


    public function destroy(Labor $labor)
    {
        $labor->delete();

        return redirect()->route('labors.index')
            ->with('success', 'Servicio eliminado.');
    }
}
