<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branches;
use Illuminate\Support\Facades\Validator;

class branchController extends Controller
{
    // Obtener todas las sucursales
    public function index()
    {
        $branches = Branches::all();

        return response()->json([
            'branches' => $branches,
            'status' => '200',
        ], 200);
    }

    // Crear una nueva sucursal
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string|unique:branches,phone',
            'schedule' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        $branch = Branches::create($request->all());

        return response()->json([
            'message' => 'Sucursal creada correctamente',
            'branch' => $branch,
            'status' => '201',
        ], 201);
    }

    // Mostrar una sucursal específica
    public function show($id)
    {
        $branch = Branches::find($id);

        if (!$branch) {
            return response()->json([
                'message' => 'Sucursal no encontrada',
                'status' => '404',
            ], 404);
        }

        return response()->json([
            'branch' => $branch,
            'status' => '200',
        ], 200);
    }

    // Actualizar una sucursal
    public function update(Request $request, $id)
    {
        $branch = Branches::find($id);

        if (!$branch) {
            return response()->json([
                'message' => 'Sucursal no encontrada',
                'status' => '404',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string|unique:branches,phone,' . $id,
            'schedule' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        $branch->update($request->all());

        return response()->json([
            'message' => 'Sucursal actualizada correctamente',
            'branch' => $branch,
            'status' => '200',
        ], 200);
    }

    // Actualización parcial
    public function updatePartial(Request $request, $id)
    {
        $branch = Branches::find($id);

        if (!$branch) {
            return response()->json([
                'message' => 'Sucursal no encontrada',
                'status' => '404',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string',
            'address' => 'sometimes|required|string',
            'phone' => 'sometimes|required|string|unique:branches,phone,' . $id,
            'schedule' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        $branch->fill($request->all());
        $branch->save();

        return response()->json([
            'message' => 'Sucursal actualizada parcialmente',
            'branch' => $branch,
            'status' => '200',
        ], 200);
    }

    // Eliminar una sucursal
    public function destroy($id)
    {
        $branch = Branches::find($id);

        if (!$branch) {
            return response()->json([
                'message' => 'Sucursal no encontrada',
                'status' => '404',
            ], 404);
        }

        $branch->delete();

        return response()->json([
            'message' => 'Sucursal eliminada correctamente',
            'status' => '200',
        ], 200);
    }
}
