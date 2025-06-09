<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vet;
use Illuminate\Support\Facades\Validator;

class vetController extends Controller
{
    //Obtener todos los veterinarios
    public function getVets()
    {
        $vets = Vet::all();
        return response()->json([
            'pets' => $vets,
            'status' => '200',
        ], 200);
    }

    //Encontrar una mascota por ID
    public function getVetById($id)
    {
        $vet = Vet::find($id);

        if (!$vet) {
            return response()->json([
                'message' => 'Veterinario no encontrado',
                'status' => '404',
            ], 404);
        }

        $data = [
            'vet' => $vet,
            'status' => '200',
        ];

        return response()->json($data, 200);
    }

    // Crear un nuevo veterinario
    public function createVet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "user_id" => "required|exists:users,id",
            "phone" => "required|unique:vets,phone",
            "birthdate" => "required|date",
            "license_number" => "required|unique:vets,license_number",
            "speciality" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        $vet = Vet::create([
            'user_id' => $request->user_id,
            'phone' => $request->phone,
            'birthdate' => $request->birthdate,
            'license_number' => $request->license_number,
            'speciality' => $request->speciality,
        ]);

        return response()->json([
            'vet' => $vet,
            'status' => '201',
        ], 201);
    }

    //Actualizar un veterinario
    public function updateVet(Request $request, $id)
    {
        $vet = Vet::find($id);

        if (!$vet) {
            return response()->json([
                'message' => 'Veterinario no encontrado',
                'status' => '404',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            "user_id" => "required|exists:users,id",
            "phone" => "required|unique:vets,phone",
            "birthdate" => "required|date",
            "license_number" => "required|unique:vets,license_number",
            "speciality" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        $vet->update([
            'user_id' => $request->user_id,
            'phone' => $request->phone,
            'birthdate' => $request->birthdate,
            'license_number' => $request->license_number,
            'speciality' => $request->speciality,
        ]);

        return response()->json([
            'message' => 'Veterinario actualizado correctamente',
            'vet' => $vet,
            'status' => '200',
        ], 200);
    }

    //Actualizar parcialmente un veterinario
    public function updateVetPartial(Request $request, $id)
    {
        $vet = Vet::find($id);

        if (!$vet) {
            return response()->json([
                'message' => 'Veterinario no encontrado',
                'status' => '404',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            "user_id" => "sometimes|required|exists:users,id",
            "phone" => "sometimes|required|unique:vets,phone",
            "birthdate" => "sometimes|required|date",
            "license_number" => "sometimes|required|unique:vets,license_number",
            "speciality" => "sometimes|required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        if ($request->has('user_id')) $vet->user_id = $request->user_id;
        if ($request->has('phone')) $vet->phone = $request->phone;
        if ($request->has('birthdate')) $vet->birthdate = $request->birthdate;
        if ($request->has('license_number')) $vet->license_number = $request->license_number;
        if ($request->has('speciality')) $vet->speciality = $request->speciality;

        $vet->save();

        return response()->json([
            'message' => 'Veterinario actualizado correctamente',
            'vet' => $vet,
            'status' => '200',
        ], 200);
    }

    //Eliminar una veterinario
    public function deleteVet($id)
    {
        $vet = Vet::find($id);

        if (!$vet) {
            $data = [
                'message' => 'Veterinario no encontrado',
                'status' => '404',
            ];
            return response()->json($data, 404);
        }

        $vet->delete();

        $data = [
            'message' => 'Veterinario eliminado correctamente',
            'status' => '200',
        ];

        return response()->json($data, 200);
    }
}
