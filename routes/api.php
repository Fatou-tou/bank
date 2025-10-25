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

// Groupe Version 1 des routes pour les comptes
route::prefix('v1')->group(function (){
    Route::apiResource('/comptes', CompteController::class);
});

