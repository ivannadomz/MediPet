<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ownerController extends Controller
{
    //Obtener todos los dueños de mascotas
    public function getOwners()
    {
        $owners = Owner::with('user')->get();
        return response()->json([
            'pets' => $owners,
            'status' => '200',
        ], 200);
    }

    //Encontrar un dueño por ID
    public function getOwnerById($id)
    {
        $owner = Owner::with('user')->find($id);

        if (!$owner) {
            return response()->json([
                'message' => 'Dueño no encontrado',
                'status' => '404',
            ], 404);
        }

        $data = [
            'owner' => $owner,
            'status' => '200',
        ];

        return response()->json($data, 200);
    }

    
    // Crear un nuevo dueño de mascota
    public function createOwner(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Validación para usuario
            "name" => "required|string|max:255",
            "email" => "required|email|unique:users,email",
            "password" => "required|string|min:6",
            // Validación para dueño
            "phone" => "required|unique:owners,phone",
            "address" => "required|string|max:255"
        ]);

        // Verificar si la validación falla
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Crear usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            // Crear dueño de mascota
            $owner = Owner::create([
                'user_id' => $user->id,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            // Asignar rol de owner al usuario
            $user->assignRole('owner');

            DB::commit();
            // Cargar la relación del usuario en el dueño
            $owner->load('user');

            // Retornar la respuesta con el dueño creado
            return response()->json([
                'owner' => $owner,
                'status' => '201',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al crear el dueño',
                'error' => $e->getMessage(),
                'status' => '500',
            ], 500);
        }
    }


    //Actualizar un dueño de mascota
    public function updateOwner(Request $request, $id)
    {
        $owner = Owner::find($id);

        if (!$owner) {
            return response()->json([
                'message' => 'Dueño no encontrado',
                'status' => '404',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            // Validaciones para usuario
            "name" => "sometimes|required|string|max:255",
            "email" => "sometimes|required|email|unique:users,email," . $owner->user_id,
            "password" => "sometimes|required|string|min:6",
            // Validación para dueño
            "phone" => "required|unique:owners,phone," . $owner->id,
            "address" => "required|string|max:255"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        // Actualizar datos del usuario relacionado
        $user = User::find($owner->user_id);
        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('password')) $user->password = bcrypt($request->password);
        $user->save();

        // Actualizar datos del dueño
        $owner->update([
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $owner->load('user');

        return response()->json([
            'message' => 'Veterinario actualizado correctamente',
            'owner' => $owner,
            'status' => '200',
        ], 200);
    }


    //Actualizar parcialmente un dueño
    public function updateOwnerPartial(Request $request, $id)
    {
        $owner = Owner::find($id);

        if (!$owner) {
            return response()->json([
                'message' => 'Dueño no encontrado',
                'status' => '404',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            // Validaciones para usuario
            "name" => "sometimes|required|string|max:255",
            "email" => "sometimes|required|email|unique:users,email," . $owner->user_id,
            "password" => "sometimes|required|string|min:6",
            // Validaciones para owner
            "phone" => "sometimes|required|unique:owners,phone," . $owner->id,
            'address' => "sometimes|required|string|max:255"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        // Actualizar datos del usuario relacionado
        $user = User::find($owner->user_id);
        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('password')) $user->password = bcrypt($request->password);
        $user->save();

        // Actualizar datos del dueño
        if ($request->has('phone')) $owner->phone = $request->phone;
        if ($request->has('address')) $owner->address = $request->address;
        $owner->save();

        $owner->load('user');

        return response()->json([
            'message' => 'Dueño actualizado correctamente',
            'owner' => $owner,
            'status' => '200',
        ], 200);
    }

    //Eliminar un owner
    public function deleteOwner($id)
    {
        $owner = Owner::find($id);

        if (!$owner) {
            $data = [
                'message' => 'Owenr no encontrado',
                'status' => '404',
            ];
            return response()->json($data, 404);
        }

        $owner->delete();

        $data = [
            'message' => 'Dueño eliminado correctamente',
            'status' => '200',
        ];

        return response()->json($data, 200);
    }
}
