<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        return Appointment::with('customer')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_type' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $appointment = Appointment::create([
            'customer_id' => $request->customer_id,
            'service_type' => $request->service_type,
            'appointment_date' => $request->appointment_date,
            'notes' => $request->notes,
            'status' => 'scheduled',
        ]);

        return response()->json($appointment->load('customer'), 201);
    }

    public function show(Appointment $appointment)
    {
        return $appointment->load('customer');
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'service_type' => 'sometimes|string|max:255',
            'appointment_date' => 'sometimes|date',
            'notes' => 'nullable|string',
            'status' => 'sometimes|in:scheduled,completed,cancelled,no-show',
        ]);

        $appointment->update($request->all());

        return response()->json($appointment->load('customer'));
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->json(null, 204);
    }
}