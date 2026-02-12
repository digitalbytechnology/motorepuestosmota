<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Client;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['client','vehicle'])
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $vehicles = Vehicle::orderBy('placa')->get();

        return view('orders.create', compact('clients','vehicles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id'  => ['nullable','exists:clients,id'],
            'vehicle_id' => ['nullable','exists:vehicles,id'],
            'status'     => ['required','in:abierta,proceso,finalizada,entregada'],
            'notes'      => ['nullable','string'],
        ]);

        $data['created_by'] = auth()->id();

        $order = Order::create($data);

        return redirect()
            ->route('orders.edit', $order)
            ->with('success','Orden creada.');
    }

    public function edit(Order $order)
    {
        $order->load(['client','vehicle','inspection']);
        $clients = Client::orderBy('name')->get();
        $vehicles = Vehicle::orderBy('placa')->get();

        return view('orders.edit', compact('order','clients','vehicles'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'client_id'  => ['nullable','exists:clients,id'],
            'vehicle_id' => ['nullable','exists:vehicles,id'],
            'status'     => ['required','in:abierta,proceso,finalizada,entregada'],
            'notes'      => ['nullable','string'],
        ]);

        $order->update($data);

        return back()->with('success','Orden actualizada.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success','Orden eliminada.');
    }
}
