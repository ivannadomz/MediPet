<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\VetController;

// Rutas para las mascotas
Route::get('/pets', [PetController::class, 'index']);

Route::get('/pets/{id}', [PetController::class,'show']);

Route::post('/pets', [PetController::class, 'store']);

Route::put('/pets/{id}', [PetController::class, 'update']);

Route::patch('/pets/{id}', [PetController::class, 'updatePartial']);

Route::delete('/pets/{id}',[PetController::class, 'destroy']);

//Rutas para los veterinarios
Route::get('/vets', [VetController::class, 'getVets']);

Route::get('/vets/{id}', [VetController::class, 'getVetById']);

Route::post('/vets', [VetController::class, 'createVet']);

Route::put('/vets/{id}', [VetController::class, 'updateVet']);

Route::patch('/vets/{id}', [VetController::class, 'updateVetPartial']);

Route::delete('/vets/{id}', [VetController::class, 'deleteVet']);