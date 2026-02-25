<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    /**
     * Display a listing of the pets.
     */
    public function index()
    {
        $pets = Pet::whereHas('client', function($query) {
            $query->where('user_id', Auth::id());
        })->with('client')->paginate(15);
        
        return view('pets.index', compact('pets'));
    }

    /**
     * Show the form for creating a new pet.
     */
    public function create()
    {
        $clients = Client::where('user_id', Auth::id())->get();
        
        return view('pets.create', compact('clients'));
    }

    /**
     * Store a newly created pet in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'name' => ['required', 'string', 'max:255'],
            'species' => ['required', 'string', 'max:100'],
            'breed' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'gender' => ['nullable', 'in:male,female'],
            'color' => ['nullable', 'string', 'max:100'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'medical_notes' => ['nullable', 'string'],
        ]);

        // Verificar que el cliente pertenece al usuario autenticado
        $client = Client::findOrFail($validated['client_id']);
        if ($client->user_id !== Auth::id()) {
            abort(403);
        }

        Pet::create($validated);

        return redirect()->route('pets.index')
            ->with('success', 'Mascota registrada exitosamente.');
    }

    /**
     * Display the specified pet.
     */
    public function show(Pet $pet)
    {
        // Verificar que la mascota pertenece a un cliente del usuario autenticado
        if ($pet->client->user_id !== Auth::id()) {
            abort(403);
        }

        $pet->load('client');
        
        return view('pets.show', compact('pet'));
    }

    /**
     * Show the form for editing the specified pet.
     */
    public function edit(Pet $pet)
    {
        // Verificar que la mascota pertenece a un cliente del usuario autenticado
        if ($pet->client->user_id !== Auth::id()) {
            abort(403);
        }

        $clients = Client::where('user_id', Auth::id())->get();
        
        return view('pets.edit', compact('pet', 'clients'));
    }

    /**
     * Update the specified pet in storage.
     */
    public function update(Request $request, Pet $pet)
    {
        // Verificar que la mascota pertenece a un cliente del usuario autenticado
        if ($pet->client->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'name' => ['required', 'string', 'max:255'],
            'species' => ['required', 'string', 'max:100'],
            'breed' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'gender' => ['nullable', 'in:male,female'],
            'color' => ['nullable', 'string', 'max:100'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'medical_notes' => ['nullable', 'string'],
        ]);

        // Verificar que el nuevo cliente también pertenece al usuario autenticado
        $client = Client::findOrFail($validated['client_id']);
        if ($client->user_id !== Auth::id()) {
            abort(403);
        }

        $pet->update($validated);

        return redirect()->route('pets.show', $pet)
            ->with('success', 'Mascota actualizada exitosamente.');
    }

    /**
     * Remove the specified pet from storage.
     */
    public function destroy(Pet $pet)
    {
        // Verificar que la mascota pertenece a un cliente del usuario autenticado
        if ($pet->client->user_id !== Auth::id()) {
            abort(403);
        }

        $pet->delete();

        return redirect()->route('pets.index')
            ->with('success', 'Mascota eliminada exitosamente.');
    }
}
