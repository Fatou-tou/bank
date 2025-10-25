<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (\App\Exceptions\CompteNonTrouveException $e, $request) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => 'COMPTE_NON_TROUVE'
            ], $e->getCode());
        });

        $this->renderable(function (\App\Exceptions\SoldeInsuffisantException $e, $request) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => 'SOLDE_INSUFFISANT'
            ], $e->getCode());
        });

        $this->renderable(function (\App\Exceptions\CompteBloqueException $e, $request) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => 'COMPTE_BLOQUE'
            ], $e->getCode());
        });

        $this->renderable(function (\App\Exceptions\TypeCompteInvalideException $e, $request) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => 'TYPE_COMPTE_INVALIDE'
            ], $e->getCode());
        });
    }
}
