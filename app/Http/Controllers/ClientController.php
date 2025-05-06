<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return view('clients.index', [
            'clients' => Client::latest()->paginate(10)
        ]);
    }

    public function create()
    {
        return view('clients.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Client::create($request->only('name'));

        return redirect()->route('clients.index')
            ->with('success', 'Client created successfully.');
    }

    public function show(Client $client)
    {
        return view('clients.show', [
            'client' => $client,
            'invoices' => $client->invoices()->with('items.product')->latest()->paginate(5) // Load invoices with items
        ]);
    }

    public function edit(Client $client)
    {
        return view('clients.form', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $client->update($request->only('name'));

        return redirect()->route('clients.index')
            ->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client moved to trash.');
    }

    public function restore($id)
    {
        Client::withTrashed()->findOrFail($id)->restore();

        return redirect()->route('clients.index')
            ->with('success', 'Client restored successfully.');
    }

    public function forceDelete($id)
    {
        Client::withTrashed()->findOrFail($id)->forceDelete();

        return redirect()->route('clients.index')
            ->with('success', 'Client permanently deleted.');
    }
}
