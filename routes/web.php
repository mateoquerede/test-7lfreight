<?php

use App\Http\Controllers\InicioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [InicioController::class, 'inicio']);
Route::post('/agregar-reservas', [InicioController::class, 'agregarReservas']);
Route::get('/consultar-reservas', [InicioController::class, 'consultarReservas']);
Route::delete('/cancelar-reserva/{id}', [InicioController::class, 'cancelarReserva']);
