<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\VetController;
use App\Http\Controllers\Api\OwnerController;

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

//Rutas para los dueños de mascotas
Route::get('/owners', [OwnerController::class, 'getOwners']);

Route::get('/owners/{id}', [OwnerController::class, 'getOwnerById']);

Route::post('/owners', [OwnerController::class, 'createOwner']);

Route::put('/owners/{id}', [OwnerController::class, 'updateOwner']);

Route::patch('/owners/{id}', [OwnerController::class, 'updateOwnerPartial']);

Route::delete('/owners/{id}', [OwnerController::class, 'deleteOwner']);