<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PetController;


Route::get('/pets', [PetController::class, 'index']);


Route::get('/pets/{id}', [PetController::class,'show']);

Route::post('/pets', [PetController::class, 'store']);

Route::put('/pets/{id}', [PetController::class, 'update']);

Route::patch('/pets/{id}', [PetController::class, 'updatePartial']);

Route::delete('/pets/{id}',[PetController::class, 'destroy']);
