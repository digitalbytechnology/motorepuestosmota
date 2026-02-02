<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::latest()->paginate(15);
        return view('vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'chasis'     => ['nullable','string','max:60'],
            'placa'      => ['nullable','string','max:20','unique:vehicles,placa'],
            'uso'        => ['nullable','string','max:50'],
            'tipo'       => ['nullable','string','max:50'],
            'accionado'  => ['nullable','string','max:50'],
            'marca'      => ['nullable','string','max:60'],
            'modelo'     => ['nullable','string','max:60'],
            'linea'      => ['nullable','string','max:60'],
            'no_serie'   => ['nullable','string','max:80'],
            'no_motor'   => ['nullable','string','max:80'],
            'color'      => ['nullable','string','max:40'],
            'cilindrada' => ['nullable','integer','min:1','max:100000'],
            'cilindros'  => ['nullable','integer','min:1','max:20'],
            'tonelaje'   => ['nullable','numeric','min:0','max:9999'],
            'asientos'   => ['nullable','integer','min:1','max:99'],
            'ejes'       => ['nullable','integer','min:1','max:20'],
            'puertas'    => ['nullable','integer','min:0','max:10'],
        ]);

        Vehicle::create($data);

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo creado.');
    }

    public function show(Vehicle $vehiculo)
    {
        return view('vehicles.show', compact('vehiculo'));
    }

    public function edit(Vehicle $vehiculo)
    {
        return view('vehicles.edit', compact('vehiculo'));
    }

    public function update(Request $request, Vehicle $vehiculo)
    {
        $data = $request->validate([
            'chasis'     => ['nullable','string','max:60'],
            'placa'      => ['nullable','string','max:20','unique:vehicles,placa,'.$vehiculo->id],
            'uso'        => ['nullable','string','max:50'],
            'tipo'       => ['nullable','string','max:50'],
            'accionado'  => ['nullable','string','max:50'],
            'marca'      => ['nullable','string','max:60'],
            'modelo'     => ['nullable','string','max:60'],
            'linea'      => ['nullable','string','max:60'],
            'no_serie'   => ['nullable','string','max:80'],
            'no_motor'   => ['nullable','string','max:80'],
            'color'      => ['nullable','string','max:40'],
            'cilindrada' => ['nullable','integer','min:1','max:100000'],
            'cilindros'  => ['nullable','integer','min:1','max:20'],
            'tonelaje'   => ['nullable','numeric','min:0','max:9999'],
            'asientos'   => ['nullable','integer','min:1','max:99'],
            'ejes'       => ['nullable','integer','min:1','max:20'],
            'puertas'    => ['nullable','integer','min:0','max:10'],
        ]);

        $vehiculo->update($data);

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo actualizado.');
    }

    public function destroy(Vehicle $vehiculo)
    {
        $vehiculo->delete();
        return redirect()->route('vehiculos.index')->with('success', 'Vehículo eliminado.');
    }
}
