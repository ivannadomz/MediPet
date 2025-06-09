<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vet;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class vetController extends Controller
{
    //Obtener todos los veterinarios
    public function getVets()
    {
        $vets = Vet::with('user')->get();
        return response()->json([
            'pets' => $vets,
            'status' => '200',
        ], 200);
    }

    //Encontrar un veterinario por ID
    public function getVetById($id)
    {
        $vet = Vet::with('user')->find($id);

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
            // Validación para usuario
            "name" => "required|string|max:255",
            "email" => "required|email|unique:users,email",
            "password" => "required|string|min:6",
            // Validación para veterinario
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

        // Crear usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Crear veterinario
        $vet = Vet::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'birthdate' => $request->birthdate,
            'license_number' => $request->license_number,
            'speciality' => $request->speciality,
        ]);

        $user->assignRole('vet'); // Asigna el rol

        $vet->load('user');

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
            // Validaciones para veterinario
            "phone" => "required|unique:vets,phone," . $vet->id,
            "birthdate" => "required|date",
            "license_number" => "required|unique:vets,license_number," . $vet->id,
            "speciality" => "required",
            // Validaciones para usuario
            "name" => "sometimes|required|string|max:255",
            "email" => "sometimes|required|email|unique:users,email," . $vet->user_id,
            "password" => "sometimes|required|string|min:6",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        // Actualizar datos del usuario relacionado
        $user = User::find($vet->user_id);
        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('password')) $user->password = bcrypt($request->password);
        $user->save();

        // Actualizar datos del veterinario
        $vet->update([
            'phone' => $request->phone,
            'birthdate' => $request->birthdate,
            'license_number' => $request->license_number,
            'speciality' => $request->speciality,
        ]);
        $vet->load('user');

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
            // Validaciones para veterinario
            "phone" => "sometimes|required|unique:vets,phone," . $vet->id,
            "birthdate" => "sometimes|required|date",
            "license_number" => "sometimes|required|unique:vets,license_number," . $vet->id,
            "speciality" => "sometimes|required",
            // Validaciones para usuario
            "name" => "sometimes|required|string|max:255",
            "email" => "sometimes|required|email|unique:users,email," . $vet->user_id,
            "password" => "sometimes|required|string|min:6",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        // Actualizar datos del usuario relacionado
        $user = User::find($vet->user_id);
        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('password')) $user->password = bcrypt($request->password);
        $user->save();

        // Actualizar datos del veterinario
        if ($request->has('phone')) $vet->phone = $request->phone;
        if ($request->has('birthdate')) $vet->birthdate = $request->birthdate;
        if ($request->has('license_number')) $vet->license_number = $request->license_number;
        if ($request->has('speciality')) $vet->speciality = $request->speciality;

        $vet->save();
        $vet->load('user');

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
