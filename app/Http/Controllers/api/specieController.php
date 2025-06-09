<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Specie;
use Illuminate\Support\Facades\Validator;

class SpecieController extends Controller
{
    // Obtener todas las especies
    public function index()
    {
        $species = Specie::all();

        return response()->json([
            'species' => $species,
            'status' => '200',
        ], 200);
    }

    // Crear una nueva especie
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'specie' => 'required|string|unique:species,specie',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        $specie = Specie::create([
            'specie' => $request->specie,
        ]);

        return response()->json([
            'message' => 'Especie creada correctamente',
            'specie' => $specie,
            'status' => '201',
        ], 201);
    }

    // Mostrar una especie específica
    public function show($id)
    {
        $specie = Specie::find($id);

        if (!$specie) {
            return response()->json([
                'message' => 'Especie no encontrada',
                'status' => '404',
            ], 404);
        }

        return response()->json([
            'specie' => $specie,
            'status' => '200',
        ], 200);
    }

    // Actualizar una especie
    public function update(Request $request, $id)
    {
        $specie = Specie::find($id);

        if (!$specie) {
            return response()->json([
                'message' => 'Especie no encontrada',
                'status' => '404',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'specie' => 'required|string|unique:species,specie,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        $specie->specie = $request->specie;
        $specie->save();

        return response()->json([
            'message' => 'Especie actualizada correctamente',
            'specie' => $specie,
            'status' => '200',
        ], 200);
    }

    // Actualización parcial de una especie
    public function updatePartial(Request $request, $id)
    {
        $specie = Specie::find($id);

        if (!$specie) {
            return response()->json([
                'message' => 'Especie no encontrada',
                'status' => '404',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'specie' => 'sometimes|required|string|unique:species,specie,' . $id,
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Error en la validación',
            'errors' => $validator->errors(),
            'status' => '400',
        ], 400);
    }

    if ($request->has('specie')) {
        $specie->specie = $request->specie;
    }

    $specie->save();

    return response()->json([
        'message' => 'Especie actualizada parcialmente',
        'specie' => $specie,
        'status' => '200',
    ], 200);
}

    // Eliminar una especie
    public function destroy($id)
    {
        $specie = Specie::find($id);

        if (!$specie) {
            return response()->json([
                'message' => 'Especie no encontrada',
                'status' => '404',
            ], 404);
        }

        $specie->delete();

        return response()->json([
            'message' => 'Especie eliminada correctamente',
            'status' => '200',
        ], 200);
    }
}
