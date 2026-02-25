<?php

namespace App\Http\Controllers;

use App\Mail\AppointmentConfirmation;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Pet;
use App\Notifications\AppointmentCancelled;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the appointments.
     */
    public function index(Request $request)
    {
        $view = $request->get('view', 'week'); // week o month
        $date = $request->get('date', now()->format('Y-m-d'));
        $currentDate = Carbon::parse($date);

        if ($view === 'week') {
            $startDate = $currentDate->copy()->startOfWeek();
            $endDate = $currentDate->copy()->endOfWeek();
        }
        else {
            $startDate = $currentDate->copy()->startOfMonth();
            $endDate = $currentDate->copy()->endOfMonth();
        }

        $appointments = Appointment::where('user_id', Auth::id())
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->with(['client', 'pet'])
            ->orderBy('appointment_date')
            ->get();

        return view('appointments.index', compact('appointments', 'view', 'currentDate', 'startDate', 'endDate'));
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create()
    {
        $clients = Client::where('user_id', Auth::id())->with('pets')->get();

        return view('appointments.create', compact('clients'));
    }

    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'pet_id' => ['required', 'exists:pets,id'],
            'appointment_date' => ['required', 'date', 'after:now'],
            'duration' => ['required', 'integer', 'min:15', 'max:240'],
            'reason' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);

        // Verificar que el cliente y la mascota pertenecen al usuario autenticado
        $client = Client::findOrFail($validated['client_id']);
        if ($client->user_id !== Auth::id()) {
            abort(403);
        }

        $pet = Pet::findOrFail($validated['pet_id']);
        if ($pet->client_id !== $client->id) {
            return back()->withErrors(['pet_id' => 'La mascota no pertenece al cliente seleccionado.']);
        }

        $validated['user_id'] = Auth::id();

        $appointment = Appointment::create($validated);
        $appointment->load(['client', 'pet']);

        // Enviar confirmación al cliente
        Mail::to($appointment->client->email)->send(
            new AppointmentConfirmation(
            $appointment,
            $appointment->client->name,
            'Tu cita ha sido registrada exitosamente en nuestra clínica veterinaria.'
            )
        );

        // Enviar confirmación al veterinario
        Mail::to(Auth::user()->email)->send(
            new AppointmentConfirmation(
            $appointment,
            Auth::user()->name,
            'Se ha registrado una nueva cita con el siguiente detalle.'
            )
        );

        return redirect()->route('appointments.index')
            ->with('success', 'Cita registrada exitosamente.');
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment)
    {
        // Verificar que la cita pertenece al usuario autenticado
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        $appointment->load(['client', 'pet']);

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     */
    public function edit(Appointment $appointment)
    {
        // Verificar que la cita pertenece al usuario autenticado
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        $clients = Client::where('user_id', Auth::id())->with('pets')->get();

        return view('appointments.edit', compact('appointment', 'clients'));
    }

    /**
     * Update the specified appointment in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        // Verificar que la cita pertenece al usuario autenticado
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'pet_id' => ['required', 'exists:pets,id'],
            'appointment_date' => ['required', 'date'],
            'duration' => ['required', 'integer', 'min:15', 'max:240'],
            'reason' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);

        // Verificar que el cliente pertenece al usuario autenticado
        $client = Client::findOrFail($validated['client_id']);
        if ($client->user_id !== Auth::id()) {
            abort(403);
        }

        $pet = Pet::findOrFail($validated['pet_id']);
        if ($pet->client_id !== $client->id) {
            return back()->withErrors(['pet_id' => 'La mascota no pertenece al cliente seleccionado.']);
        }

        $previousStatus = $appointment->status;
        $appointment->update($validated);

        // Si el status cambió a cancelado, notificar a ambos
        if ($validated['status'] === 'cancelled' && $previousStatus !== 'cancelled') {
            $appointment->load(['client', 'pet', 'user']);

            // Notificar al cliente
            $appointment->client->notify(
                new AppointmentCancelled($appointment, 'client')
            );

            // Notificar al veterinario
            $appointment->user->notify(
                new AppointmentCancelled($appointment, 'user')
            );
        }

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Cita actualizada exitosamente.');
    }

    /**
     * Remove the specified appointment from storage.
     */
    public function destroy(Appointment $appointment)
    {
        // Verificar que la cita pertenece al usuario autenticado
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Cita eliminada exitosamente.');
    }

    /**
     * Get pets for a specific client (AJAX).
     */
    public function getPets($clientId)
    {
        $client = Client::where('id', $clientId)
            ->where('user_id', Auth::id())
            ->with('pets')
            ->firstOrFail();

        return response()->json($client->pets);
    }
}
