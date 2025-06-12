<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\Owner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PetController extends Controller
{
    // Obtener solo las mascotas del usuario autenticado
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $owner = Owner::where('user_id', $user->id)->first();
        if (!$owner) {
            return response()->json(['message' => 'Dueño no encontrado'], 404);
        }

        // Cargar especie y raza con relaciones (specie, race)
        $pets = Pet::with(['specie', 'race'])->where('owner_id', $owner->id)->get();

        return response()->json(['pets' => $pets], 200);
    }

    // Obtener mascotas por owner_id
    public function petsByOwner($ownerId)
    {
        $pets = Pet::where('owner_id', $ownerId)->get();

        if ($pets->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron mascotas para este dueño',
                'status' => '404',
            ], 404);
        }

        return response()->json([
            'pets' => $pets,
            'status' => '200',
        ], 200);
    }

    // Crear una nueva mascota
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'birthdate' => 'required|date',
            'gender' => 'required|string',
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

        $pet = Pet::create($request->only([
            'name', 'birthdate', 'gender', 'weight', 'allergies',
            'species_id', 'owner_id', 'race_id'
        ]));

        return response()->json([
            'pet' => $pet,
            'status' => '201',
        ], 201);
    }

    // Mostrar una mascota por ID
    public function show($id)
{
    $pet = Pet::with(['specie', 'race'])->find($id);

    if (!$pet) {
        return response()->json([
            'message' => 'Mascota no encontrada',
            'status' => '404',
        ], 404);
    }

    return response()->json([
        'pet' => $pet,
        'status' => '200',
    ], 200);
}

    // Actualizar todos los campos
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
            'gender' => 'required|string',
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

        $pet->update($request->only([
            'name', 'birthdate', 'gender', 'weight', 'allergies',
            'species_id', 'owner_id', 'race_id'
        ]));

        return response()->json([
            'message' => 'Mascota actualizada correctamente',
            'pet' => $pet,
            'status' => '200',
        ], 200);
    }

    // Actualización parcial
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
            'gender' => 'sometimes|required|string',
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

        $pet->update($request->only([
            'name', 'birthdate', 'gender', 'weight', 'allergies',
            'species_id', 'owner_id', 'race_id'
        ]));

        return response()->json([
            'message' => 'Mascota actualizada correctamente',
            'pet' => $pet,
            'status' => '200',
        ], 200);
    }

    // Eliminar mascota
    public function destroy($id)
    {
        $pet = Pet::find($id);

        if (!$pet) {
            return response()->json([
                'message' => 'Mascota no encontrada',
                'status' => '404',
            ], 404);
        }

        $pet->delete();

        return response()->json([
            'message' => 'Mascota eliminada correctamente',
            'status' => '200',
        ], 200);
    }
}
