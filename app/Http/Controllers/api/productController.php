<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products; // Modelo en plural
use Illuminate\Support\Facades\Validator;

class productController extends Controller
{

    //Mostrar todos 
    public function index()
    {
        $products = Products::all();

        return response()->json([
            'products' => $products,
            'status' => '200',
        ], 200);
    }


    // Crear un nuevo producto

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'integer|min:0',
            'category' => 'required|string',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        $product = Products::create($request->all());

        return response()->json([
            'message' => 'Producto creado correctamente',
            'product' => $product,
            'status' => '201',
        ], 201);
    }

    // Mostrar un producto específico

    public function show($id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Producto no encontrado',
                'status' => '404',
            ], 404);
        }

        return response()->json([
            'product' => $product,
            'status' => '200',
        ], 200);
    }
    // Actualizar un producto completamente

    public function update(Request $request, $id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Producto no encontrado',
                'status' => '404',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'integer|min:0',
            'category' => 'required|string',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        $product->update($request->all());

        return response()->json([
            'message' => 'Producto actualizado correctamente',
            'product' => $product,
            'status' => '200',
        ], 200);
    }

    // Actualizar un producto parcialmente
    public function updatePartial(Request $request, $id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Producto no encontrado',
                'status' => '404',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'branch_id' => 'sometimes|exists:branches,id',
            'name' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'stock' => 'sometimes|integer|min:0',
            'category' => 'sometimes|string',
            'description' => 'sometimes|nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en validación',
                'errors' => $validator->errors(),
                'status' => '400',
            ], 400);
        }

        $product->fill($request->all());
        $product->save();

        return response()->json([
            'message' => 'Producto actualizado parcialmente',
            'product' => $product,
            'status' => '200',
        ], 200);
    }

    // Eliminar un producto
    public function destroy($id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Producto no encontrado',
                'status' => '404',
            ], 404);
        }

        $product->delete();

        return response()->json([
            'message' => 'Producto eliminado correctamente',
            'status' => '200',
        ], 200);
    }
}
