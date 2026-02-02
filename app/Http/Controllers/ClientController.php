<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Listado + búsqueda
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $clients = Client::query()
            ->when($search, function ($q) use ($search) {
                $q->where('document_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('clients.index', compact('clients', 'search'));
    }

    /**
     * Form crear
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Guardar
     */
    public function store(Request $request)
{
    $data = $request->validate([
        'document_type'   => ['required', 'string', 'max:10'],
        'document_number' => ['required', 'string', 'max:20', 'unique:clients,document_number'],
        'name'            => ['required', 'string', 'max:150'],
        'address'         => ['nullable', 'string', 'max:255'],
        'phone'           => ['nullable', 'string', 'max:30'],
        'email'           => ['nullable', 'email', 'max:150'],
        'is_active'       => ['nullable', 'boolean'],
        'notes'           => ['nullable', 'string'],
    ]);

    $data['is_active'] = $request->boolean('is_active', true);

    Client::create($data);

    return redirect()
        ->route('clientes.index')
        ->with('success', 'Cliente creado correctamente.');
}


    /**
     * Ver detalle (opcional)
     */
    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    /**
     * Form editar
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Actualizar
     */
    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'document_type'   => ['required', Rule::in(['NIT', 'DPI'])],
            'document_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('clients', 'document_number')->ignore($client->id),
            ],
            'name'      => ['required', 'string', 'max:150'],
            'address'   => ['nullable', 'string', 'max:255'],
            'phone'     => ['nullable', 'string', 'max:30'],
            'email'     => ['nullable', 'email', 'max:150'],
            'is_active' => ['nullable', 'boolean'],
            'notes'     => ['nullable', 'string'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $client->update($data);

        return redirect()
    ->route('clientes.index')
    ->with('success', 'Cliente actualizado correctamente.');

    }

    /**
     * Eliminar (si prefieres, cambia a SoftDeletes)
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()
    ->route('clientes.index')
    ->with('success', ' Cliente eliminado correctamente.');

    }

    
    public function toggleStatus(Client $client)
    {
        $client->update([
            'is_active' => ! (bool) $client->is_active,
        ]);

        $msg = $client->is_active
            ? 'Cliente activado.'
            : 'Cliente inactivado.';

        return back()->with('success', $msg);
    }
}
