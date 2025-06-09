<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Race;
use Illuminate\Support\Facades\Validator;

class RaceController extends Controller
{
    // Obtener todas las razas
    public function index()
    {
        $races = Race::with('specie')->get(); // Incluye la especie relacionada

        return response()->json([
            'races' => $races,
            'status' => '200',
        ], 200);
    }

    // Crear una nueva raza
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:races,name',
            'species_id' => 'required|exists:species,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        $race = Race::create([
            'name' => $request->name,
            'species_id' => $request->species_id,
        ]);

        return response()->json([
            'message' => 'Raza creada correctamente',
            'race' => $race,
            'status' => '201',
        ], 201);
    }

    // Mostrar una raza específica
    public function show($id)
    {
        $race = Race::with('specie')->find($id);

        if (!$race) {
            return response()->json([
                'message' => 'Raza no encontrada',
                'status' => '404',
            ], 404);
        }

        return response()->json([
            'race' => $race,
            'status' => '200',
        ], 200);
    }

    // Actualizar una raza completamente
    public function update(Request $request, $id)
    {
        $race = Race::find($id);

        if (!$race) {
            return response()->json([
                'message' => 'Raza no encontrada',
                'status' => '404',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:races,name,' . $id,
            'species_id' => 'required|exists:species,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        $race->name = $request->name;
        $race->species_id = $request->species_id;
        $race->save();

        return response()->json([
            'message' => 'Raza actualizada correctamente',
            'race' => $race,
            'status' => '200',
        ], 200);
    }

    // Actualización parcial
    public function updatePartial(Request $request, $id)
    {
        $race = Race::find($id);

        if (!$race) {
            return response()->json([
                'message' => 'Raza no encontrada',
                'status' => '404',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|unique:races,name,' . $id,
            'species_id' => 'sometimes|required|exists:species,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        if ($request->has('name')) {
            $race->name = $request->name;
        }

        if ($request->has('species_id')) {
            $race->species_id = $request->species_id;
        }

        $race->save();

        return response()->json([
            'message' => 'Raza actualizada parcialmente',
            'race' => $race,
            'status' => '200',
        ], 200);
    }

    // Eliminar una raza
    public function destroy($id)
    {
        $race = Race::find($id);

        if (!$race) {
            return response()->json([
                'message' => 'Raza no encontrada',
                'status' => '404',
            ], 404);
        }

        $race->delete();

        return response()->json([
            'message' => 'Raza eliminada correctamente',
            'status' => '200',
        ], 200);
    }
}
