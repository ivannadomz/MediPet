<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner;
use App\Models\User;
use App\Models\Pet;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ownerController extends Controller
{
    public function getOwners()
    {
        $owners = Owner::with('user')->get();
        return response()->json([
            'pets' => $owners,
            'status' => '200',
        ], 200);
    }

    public function getOwnerById($id)
    {
        $owner = Owner::with('user')->find($id);

        if (!$owner) {
            return response()->json([
                'message' => 'Dueño no encontrado',
                'status' => '404',
            ], 404);
        }

        return response()->json([
            'owner' => $owner,
            'status' => '200',
        ], 200);
    }

    public function createOwner(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "email" => "required|email|unique:users,email",
            "password" => "required|string|min:6",
            "phone" => "required|unique:owners,phone",
            "address" => "required|string|max:255"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $owner = Owner::create([
                'user_id' => $user->id,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            $user->assignRole('owner');

            DB::commit();
            $owner->load('user');

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
            "name" => "sometimes|required|string|max:255",
            "email" => "sometimes|required|email|unique:users,email," . $owner->user_id,
            "password" => "sometimes|required|string|min:6",
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

        $user = User::find($owner->user_id);
        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('password')) $user->password = bcrypt($request->password);
        $user->save();

        $owner->update([
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $owner->load('user');

        return response()->json([
            'message' => 'Dueño actualizado correctamente',
            'owner' => $owner,
            'status' => '200',
        ], 200);
    }

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
            "name" => "sometimes|required|string|max:255",
            "email" => "sometimes|required|email|unique:users,email," . $owner->user_id,
            "password" => "sometimes|required|string|min:6",
            "phone" => "sometimes|required|unique:owners,phone," . $owner->id,
            "address" => "sometimes|required|string|max:255"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        $user = User::find($owner->user_id);
        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('password')) $user->password = bcrypt($request->password);
        $user->save();

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

    public function deleteOwner($id)
    {
        $owner = Owner::find($id);

        if (!$owner) {
            return response()->json([
                'message' => 'Owner no encontrado',
                'status' => '404',
            ], 404);
        }

        DB::beginTransaction();
        try {
            // Eliminar mascotas del owner
            $owner->pets()->delete();

            // Eliminar user
            $user = User::find($owner->user_id);

            $owner->delete();

            if ($user) {
                $user->delete();
            }

            DB::commit();

            return response()->json([
                'message' => 'Dueño, mascotas y usuario eliminados correctamente',
                'status' => '200',
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al eliminar el dueño',
                'error' => $e->getMessage(),
                'status' => '500',
            ], 500);
        }
    }

    public function profile()
    {
        $user = Auth::user();
        $owner = $user->owner;

        if (!$owner) {
            return response()->json([
                'message' => 'No se encontró información del dueño',
                'status' => '404',
            ], 404);
        }

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $owner->phone,
            'address' => $owner->address,
            'status' => '200',
        ], 200);
    }

    public function getOwnerByUser($userId)
    {
    $owner = Owner::with('user')->where('user_id', $userId)->first();

    if (!$owner) {
        return response()->json([
            'message' => 'Dueño no encontrado para este usuario',
            'status' => '404',
        ], 404);
    }

    return response()->json([
        'owner' => $owner,
        'status' => '200',
    ], 200);
    }
}
