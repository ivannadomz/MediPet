<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet;
use Illuminate\Support\Facades\Validator;

class petController extends Controller
{
    public function index()
    {
        $pets = Pet::all();
        return response()->json([
            'pets' => $pets,
            'status' => '200',
        ], 200);
    }

    // Crear una nueva mascota
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'birthdate' => 'required|date',
            'gender' => 'required',
            'weight' => 'nullable|numeric',
            'allergies' => 'nullable',
            'species_id' => 'required|exists:species,id',
            'owner_id' => 'required|exists:owners,id',
            'race_id' => 'nullable|exists:races,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        $pet = Pet::create([
            'name' => $request->name,
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'weight' => $request->weight,
            'allergies' => $request->allergies,
            'species_id' => $request->species_id,
            'owner_id' => $request->owner_id,
            'race_id' => $request->race_id,
        ]);

        return response()->json([
            'pet' => $pet,
            'status' => '201',
        ], 201);
    }

    // Encontrar una mascota por ID
    public function show($id)
    {
        $pet = Pet::find($id);

        if (!$pet) {
            return response()->json([
                'message' => 'Mascota no encontrada',
                'status' => '404',
            ], 404);
        }

        $data = [
            'pet' => $pet,
            'status' => '200',
        ];

        return response()->json($data, 200);
    }

    
    // Actualizar una mascota
    public function update(Request $request, $id)
    {
        $pet = Pet::find($id);

        if (!$pet) {
            return response()->json([
                'message' => 'Mascota no encontrada',
                'status' => '404',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'birthdate' => 'required|date',
            'gender' => 'required',
            'weight' => 'nullable|numeric',
            'allergies' => 'nullable|string',
            'species_id' => 'required|exists:species,id',
            'owner_id' => 'required|exists:owners,id',
            'race_id' => 'nullable|exists:races,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        $pet->update([
            'name' => $request->name,
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'weight' => $request->weight,
            'allergies' => $request->allergies,
            'species_id' => $request->species_id,
            'owner_id' => $request->owner_id,
            'race_id' => $request->race_id,
        ]);

        return response()->json([
            'message' => 'Mascota actualizada correctamente',
            'pet' => $pet,
            'status' => '200',
        ], 200);
        }

    // Actualizar campo por separado

    public function updatePartial(Request $request, $id)
    {
        $pet = Pet::find($id);

        if (!$pet) {
            return response()->json([
                'message' => 'Mascota no encontrada',
                'status' => '404',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string',
            'birthdate' => 'sometimes|required|date',
            'gender' => 'sometimes|required',
            'weight' => 'sometimes|nullable|numeric',
            'allergies' => 'sometimes|nullable|string',
            'species_id' => 'sometimes|required|exists:species,id',
            'owner_id' => 'sometimes|required|exists:owners,id',
            'race_id' => 'sometimes|nullable|exists:races,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        if ($request->has('name')) $pet->name = $request->name;
        if ($request->has('birthdate')) $pet->birthdate = $request->birthdate;
        if ($request->has('gender')) $pet->gender = $request->gender;
        if ($request->has('weight')) $pet->weight = $request->weight;
        if ($request->has('allergies')) $pet->allergies = $request->allergies;
        if ($request->has('species_id')) $pet->species_id = $request->species_id;
        if ($request->has('owner_id')) $pet->owner_id = $request->owner_id;
        if ($request->has('race_id')) $pet->race_id = $request->race_id;

        $pet->save();

        return response()->json([
            'message' => 'Mascota actualizada correctamente',
            'pet' => $pet,
            'status' => '200',
        ], 200);
    }

    // Eliminar una mascota
    public function destroy($id)
    {
        $pet = Pet::find($id);

        if (!$pet) {
            $data = [
                'message' => 'Mascota no encontrada',
                'status' => '404',
            ];
            return response()->json($data, 404);
        }

        $pet->delete();

        $data = [
            'message' => 'Mascota eliminada correctamente',
            'status' => '200',
        ];

        return response()->json($data, 200);
    }

}