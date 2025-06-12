<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prescription;
use Illuminate\Support\Facades\Validator;

class prescriptionController extends Controller
{
    // Obtener todas las recetas
    public function index(Request $request)
    {
        // Leer el parámetro appointment_id si existe
        $appointmentId = $request->query('appointment_id');

        if ($appointmentId) {
            // Filtrar las prescripciones por appointment_id
            $prescriptions = Prescription::where('appointment_id', $appointmentId)->get();
        } else {
            // Si no hay parámetro, regresar todas las prescripciones
            $prescriptions = Prescription::all();
        }

        return response()->json([
            'prescriptions' => $prescriptions,
            'status' => 200,
        ]);
    }

    // Crear una receta
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'specifications' => 'required|string',
            'date' => 'required|date',
            'reason' => 'required|string',
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'appointment_id' => 'required|exists:appointments,id',
            'xray_file' => 'nullable|string',
            'lab_file' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => 400,
            ], 400);
        }

        $prescription = Prescription::create($request->all());

        return response()->json([
            'message' => 'Receta creada correctamente',
            'prescription' => $prescription,
            'status' => 201,
        ], 201);
    }

    // Mostrar receta por ID
    public function show($id)
    {
        $prescription = Prescription::find($id);

        if (!$prescription) {
            return response()->json([
                'message' => 'Receta no encontrada',
                'status' => 404,
            ], 404);
        }

        return response()->json([
            'prescription' => $prescription,
            'status' => 200,
        ]);
    }

    // Actualizar receta completamente
    public function update(Request $request, $id)
    {
        $prescription = Prescription::find($id);

        if (!$prescription) {
            return response()->json([
                'message' => 'Receta no encontrada',
                'status' => 404,
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'specifications' => 'required|string',
            'date' => 'required|date',
            'reason' => 'required|string',
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'appointment_id' => 'required|exists:appointments,id',
            'xray_file' => 'nullable|string',
            'lab_file' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => 400,
            ], 400);
        }

        $prescription->update($request->all());

        return response()->json([
            'message' => 'Receta actualizada correctamente',
            'prescription' => $prescription,
            'status' => 200,
        ]);
    }


    // Actualizar campos específicos de la receta
    public function updatePartial(Request $request, $id)
{
    $prescription = Prescription::find($id);

    if (!$prescription) {
        return response()->json([
            'message' => 'Receta no encontrada',
            'status' => '404',
        ], 404);
    }

    
    $updated = false;

    if ($request->has('specifications')) {
        \Log::info('Antes de actualizar specifications: ' . $prescription->specifications);
        \Log::info('Valor recibido: ' . $request->input('specifications'));

        $prescription->specifications = $request->input('specifications');
        $updated = true;
    }

    if ($request->has('date')) {
        $prescription->date = $request->input('date');
        $updated = true;
    }

    if ($request->has('reason')) {
        $prescription->reason = $request->input('reason');
        $updated = true;
    }

    if ($request->has('diagnosis')) {
        $prescription->diagnosis = $request->input('diagnosis');
        $updated = true;
    }

    if ($request->has('treatment')) {
        $prescription->treatment = $request->input('treatment');
        $updated = true;
    }

    if ($request->has('xray_file')) {
        $prescription->xray_file = $request->input('xray_file');
        $updated = true;
    }

    if ($request->has('lab_file')) {
        $prescription->lab_file = $request->input('lab_file');
        $updated = true;
    }

    if ($request->has('appointment_id')) {
        $prescription->appointment_id = $request->input('appointment_id');
        $updated = true;
    }

    if ($updated) {
        $prescription->save();

        \Log::info('Después de actualizar specifications: ' . $prescription->specifications);

        return response()->json([
            'message' => 'Receta actualizada parcialmente',
            'prescription' => $prescription,
            'status' => '200',
        ], 200);
    } else {
        return response()->json([
            'message' => 'No se proporcionaron campos para actualizar',
            'status' => '400',
        ], 400);
    }
}


    // Eliminar receta
    public function destroy($id)
    {
        $prescription = Prescription::find($id);

        if (!$prescription) {
            return response()->json([
                'message' => 'Receta no encontrada',
                'status' => 404,
            ], 404);
        }

        $prescription->delete();

        return response()->json([
            'message' => 'Receta eliminada correctamente',
            'status' => 200,
        ]);
    }
}
