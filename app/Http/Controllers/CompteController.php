<?php

namespace App\Http\Controllers;

use App\Models\Compte;
use App\Traits\RestResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\CompteCollection;

/**
 * @OA\Tag(
 *     name="Comptes",
 *     description="Gestion des comptes bancaires"
 * )
 */
class CompteController extends Controller
{
    use RestResponse;
    /**
     * Lister tous les comptes
     *
     * @OA\Get(
     *     path="/api/v1/comptes",
     *     tags={"Comptes"},
     *     summary="Lister les comptes bancaires",
     *     description="Récupère la liste paginée des comptes avec possibilité de filtrage et recherche",
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Recherche par numéro de compte",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Filtrer par type de compte (epargne, cheque)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"epargne", "cheque"})
     *     ),
     *     @OA\Parameter(
     *         name="statut",
     *         in="query",
     *         description="Filtrer par statut (actif, bloque, ferme)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"actif", "bloque", "ferme"})
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Champ de tri (dateCreation, solde, titulaire)",
     *         required=false,
     *         @OA\Schema(type="string", default="dateCreation")
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         description="Ordre de tri",
     *         required=false,
     *         @OA\Schema(type="string", enum={"asc", "desc"}, default="asc")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Nombre d'éléments par page (max 100)",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=100, default=10)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Numéro de page",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des comptes récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Comptes récupérés avec succès"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Compte")),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        // Logs pour diagnostiquer les paramètres reçus
        Log::info('Paramètres de requête reçus dans CompteController@index:', $request->all());

        $query = Compte::with('client', 'transactions')
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

        return new CompteCollection($comptes);
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
