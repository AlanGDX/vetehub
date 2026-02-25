<?php

namespace App\Http\Controllers;

use App\Mail\ClientWelcome;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ClientController extends Controller
{
    /**
     * Display a listing of the clients.
     */
    public function index()
    {
        $clients = Client::where('user_id', Auth::id())
            ->with('pets')
            ->paginate(10);

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:clients,email'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
        ]);

        $validated['user_id'] = Auth::id();

        $client = Client::create($validated);

        // Enviar email de bienvenida al cliente
        Mail::to($client->email)->send(new ClientWelcome($client));

        return redirect()->route('clients.index')
            ->with('success', 'Cliente registrado exitosamente.');
    }

    /**
     * Display the specified client.
     */
    public function show(Client $client)
    {
        // Verificar que el cliente pertenece al usuario autenticado
        if ($client->user_id !== Auth::id()) {
            abort(403);
        }

        $client->load('pets');

        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(Client $client)
    {
        // Verificar que el cliente pertenece al usuario autenticado
        if ($client->user_id !== Auth::id()) {
            abort(403);
        }

        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified client in storage.
     */
    public function update(Request $request, Client $client)
    {
        // Verificar que el cliente pertenece al usuario autenticado
        if ($client->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:clients,email,' . $client->id],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
        ]);

        $client->update($validated);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Cliente actualizado exitosamente.');
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy(Client $client)
    {
        // Verificar que el cliente pertenece al usuario autenticado
        if ($client->user_id !== Auth::id()) {
            abort(403);
        }

        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Cliente eliminado exitosamente.');
    }
}
