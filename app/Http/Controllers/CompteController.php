<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Traits\RestResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompteController extends Controller
{
    use RestResponse;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Logs pour diagnostiquer les paramètres reçus
        Log::info('Paramètres de requête reçus dans CompteController@index:', $request->all());

        $query = Compte::with('client')
            ->search($request->search)  // Utilise le trait avec colonnes définies dans le modèle
            ->filter([
                'type' => $request->type,
                'statut' => $request->statut
            ])  // Utilise le trait avec colonnes autorisées
            ->trier($request->get('sort', 'dateCreation'), $request->get('order', 'asc'));  // Scope personnalisé pour logique complexe

        Log::info('Query construite avec scopes appliqués');

        $perPage = min($request->get('limit', 10), 100); // Max 100

        Log::info('Pagination appliquée:', ['perPage' => $perPage, 'page' => $request->get('page', 1)]);

        $comptes = $query->paginateIfNeeded($perPage);  // Utilise le trait pour pagination

        Log::info('Nombre de comptes retournés:', ['count' => count($comptes->items()), 'total' => $comptes->total()]);

        return $this->paginatedSuccessResponse($comptes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
