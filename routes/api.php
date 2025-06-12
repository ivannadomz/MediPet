<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\petController;
use App\Http\Controllers\api\specieController;
use App\Http\Controllers\api\raceController;
use App\Http\Controllers\api\prescriptionController;
use App\Http\Controllers\api\appointmentController;
use App\Http\Controllers\api\branchController;
use App\Http\Controllers\api\productController;
use App\Http\Controllers\Api\vetController;
use App\Http\Controllers\Api\ownerController;
use App\Http\Controllers\Auth\AuthController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/pets', [petController::class, 'index']); // mascotas del usuario autenticado
    Route::get('/pets/{id}', [petController::class, 'show']);
    Route::post('/pets', [petController::class, 'store']);
    Route::put('/pets/{id}', [petController::class, 'update']);
    Route::patch('/pets/{id}', [petController::class, 'updatePartial']);
    Route::delete('/pets/{id}', [petController::class, 'destroy']);

    Route::get('/pets/owner/{ownerId}', [petController::class, 'petsByOwner']);
});

// Rutas para Species
Route::get('/species', [specieController::class, 'index']);
Route::get('/species/{id}', [specieController::class, 'show']);
Route::post('/species', [specieController::class, 'store']);
Route::put('/species/{id}', [specieController::class, 'update']);
Route::patch('/species/{id}', [specieController::class, 'updatePartial']);
Route::delete('/species/{id}', [specieController::class, 'destroy']);

// Rutas para Races
Route::get('/races', [raceController::class, 'index']);
Route::post('/races', [raceController::class, 'store']);
Route::get('/races/{id}', [raceController::class, 'show']);
Route::put('/races/{id}', [raceController::class, 'update']);
Route::patch('/races/{id}', [raceController::class, 'updatePartial']);
Route::delete('/races/{id}', [raceController::class, 'destroy']);
//Rutas para Prescripciones
Route::get('/prescriptions', [prescriptionController::class, 'index']);
Route::post('/prescriptions', [prescriptionController::class, 'store']);
Route::get('/prescriptions/{id}', [prescriptionController::class, 'show']);
Route::put('/prescriptions/{id}', [prescriptionController::class, 'update']);
Route::patch('/prescriptions/{id}', [prescriptionController::class, 'updatePartial']);
Route::delete('/prescriptions/{id}', [prescriptionController::class, 'destroy']);

//Rutas para Citas
Route::middleware('auth:api')->group(function () {
    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::get('/appointments/{id}', [AppointmentController::class, 'show']);
    Route::put('/appointments/{id}', [AppointmentController::class, 'update']);
    Route::patch('/appointments/{id}', [AppointmentController::class, 'updatePartial']);
    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']);
});

//Rutas para Sucursales
Route::get('/branches', [branchController::class, 'index']);
Route::post('/branches', [branchController::class, 'store']);
Route::get('/branches/{id}', [branchController::class, 'show']);
Route::put('/branches/{id}', [branchController::class, 'update']);
Route::patch('/branches/{id}', [branchController::class, 'updatePartial']);
Route::delete('/branches/{id}', [branchController::class, 'destroy']);

//Rutas para Productos
Route::get('/products', [productController::class, 'index']);
Route::post('/products', [productController::class, 'store']);
Route::get('/products/{id}', [productController::class, 'show']);
Route::put('/products/{id}', [productController::class, 'update']);
Route::patch('/products/{id}', [productController::class, 'updatePartial']);
Route::delete('/products/{id}', [productController::class, 'destroy']);

//Rutas para los veterinarios
Route::get('/vets', [vetController::class, 'getVets']);
Route::get('/vets/{id}', [vetController::class, 'getVetById']);
Route::post('/vets', [vetController::class, 'createVet']);
Route::put('/vets/{id}', [vetController::class, 'updateVet']);
Route::patch('/vets/{id}', [vetController::class, 'updateVetPartial']);
Route::delete('/vets/{id}', [vetController::class, 'deleteVet']);

//Rutas para los dueños de mascotas
Route::get('/owners', [ownerController::class, 'getOwners']);
Route::get('/owners/{id}', [ownerController::class, 'getOwnerById']);
Route::post('/owners', [ownerController::class, 'createOwner']);
Route::put('/owners/{id}', [ownerController::class, 'updateOwner']);
Route::patch('/owners/{id}', [ownerController::class, 'updateOwnerPartial']);
Route::delete('/owners/{id}', [ownerController::class, 'deleteOwner']);

Route::get('/owners/by-user/{user_id}', [OwnerController::class, 'getOwnerByUser']);

//Rutas para autenticacion 
Route::post('/register', [AuthController::class, 'register']); 
Route::post('/login', [AuthController::class, 'login']);        

// Rutas protegidas por autenticación con Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']); 
    Route::get('/user', function (Request $request) {
        return $request->user();                               
    });
});