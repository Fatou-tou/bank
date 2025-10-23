<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
|  C'est ici que vous pouvez enregistrer les routes API pour votre  
|   application. Ces
| les routes sont chargées par le RouteServiceProvider et toutes seront
| être affecté au groupe middleware "api". Faites quelque chose de génial !
|
*/


Route::apiResource('v1/comptes', CompteController::class);
