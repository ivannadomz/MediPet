<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    // Obtener todas las citas
    public function index()
    {
        $appointments = Appointment::all();

        return response()->json([
            'appointments' => $appointments,
            'status' => '200',
        ], 200);
    }

    // Crear una nueva cita
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vet_id' => 'required|exists:vets,id',
            'pet_id' => 'required|exists:pets,id',
            'branch_id' => 'required|exists:branches,id',
            'appointment_date' => 'required|date',
            'status' => 'sometimes|string',
            'reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        $appointment = Appointment::create([
            'vet_id' => $request->vet_id,
            'pet_id' => $request->pet_id,
            'branch_id' => $request->branch_id,
            'appointment_date' => $request->appointment_date,
            'status' => $request->status ?? 'scheduled',
            'reason' => $request->reason,
        ]);

        return response()->json([
            'message' => 'Cita creada correctamente',
            'appointment' => $appointment,
            'status' => '201',
        ], 201);
    }

    // Mostrar una cita específica
    public function show($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json([
                'message' => 'Cita no encontrada',
                'status' => '404',
            ], 404);
        }

        return response()->json([
            'appointment' => $appointment,
            'status' => '200',
        ], 200);
    }

    // Actualizar una cita completa
    public function update(Request $request, $id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json([
                'message' => 'Cita no encontrada',
                'status' => '404',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'vet_id' => 'required|exists:vets,id',
            'pet_id' => 'required|exists:pets,id',
            'branch_id' => 'required|exists:branches,id',
            'appointment_date' => 'required|date',
            'status' => 'sometimes|string',
            'reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        $appointment->vet_id = $request->vet_id;
        $appointment->pet_id = $request->pet_id;
        $appointment->branch_id = $request->branch_id;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->status = $request->status ?? $appointment->status;
        $appointment->reason = $request->reason;
        $appointment->save();

        return response()->json([
            'message' => 'Cita actualizada correctamente',
            'appointment' => $appointment,
            'status' => '200',
        ], 200);
    }

    // Actualización parcial de una cita
    public function updatePartial(Request $request, $id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json([
                'message' => 'Cita no encontrada',
                'status' => '404',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'vet_id' => 'sometimes|required|exists:vets,id',
            'pet_id' => 'sometimes|required|exists:pets,id',
            'branch_id' => 'sometimes|required|exists:branches,id',
            'appointment_date' => 'sometimes|required|date',
            'status' => 'sometimes|string',
            'reason' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        if ($request->has('vet_id')) {
            $appointment->vet_id = $request->vet_id;
        }
        if ($request->has('pet_id')) {
            $appointment->pet_id = $request->pet_id;
        }
        if ($request->has('branch_id')) {
            $appointment->branch_id = $request->branch_id;
        }
        if ($request->has('appointment_date')) {
            $appointment->appointment_date = $request->appointment_date;
        }
        if ($request->has('status')) {
            $appointment->status = $request->status;
        }
        if ($request->has('reason')) {
            $appointment->reason = $request->reason;
        }

        $appointment->save();

        return response()->json([
            'message' => 'Cita actualizada parcialmente',
            'appointment' => $appointment,
            'status' => '200',
        ], 200);
    }

    // Eliminar una cita
    public function destroy($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json([
                'message' => 'Cita no encontrada',
                'status' => '404',
            ], 404);
        }

        $appointment->delete();

        return response()->json([
            'message' => 'Cita eliminada correctamente',
            'status' => '200',
        ], 200);
    }
}
