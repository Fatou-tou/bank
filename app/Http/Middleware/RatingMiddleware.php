<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\RateLimitExceededException;

class RatingMiddleware
{
    /**
     * Gère le rate limiting et enregistre les utilisateurs bloqués.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Identifiant unique (utilisateur authentifié ou IP)
        $user = $request->user();
        $key = $user ? 'rate_limit:user:' . $user->id : 'rate_limit:ip:' . $request->ip();

        // Configuration du rate limit
        $maxAttempts = 2;       // Ex: 2 requêtes
        $decaySeconds = 60;     // Ex: par minute

        // Compteur actuel
        $attempts = Cache::get($key, 0);

        if ($attempts >= $maxAttempts) {
            // Enregistrer l'utilisateur qui a dépassé la limite
            $this->logRateLimitExceeded($request);

            $retryAfter = Cache::get($key . ':timer') - time();
            if ($retryAfter < 0) $retryAfter = $decaySeconds;

            throw new RateLimitExceededException($retryAfter, $request->path());
        }

        // Incrémentation du compteur
        if ($attempts === 0) {
            Cache::put($key . ':timer', time() + $decaySeconds, $decaySeconds);
        }

        Cache::put($key, $attempts + 1, $decaySeconds);

        return $next($request);
    }

    /**
     * Enregistre dans la base ou les logs les utilisateurs ayant atteint la limite.
     */
    protected function logRateLimitExceeded(Request $request)
    {
        $user = $request->user();

        // Option 1 : Enregistrement dans les logs
        Log::warning('Rate limit exceeded', [
            'user_id' => $user ? $user->id : null,
            'ip' => $request->ip(),
            'endpoint' => $request->path(),
            'timestamp' => now()->toDateTimeString(),
        ]);

        // Option 2 : Enregistrement dans la base (table rate_limits)
        DB::table('rate_limits')->insert([
            'id' => (string) Str::uuid(),
            'user_id' => $user ? $user->id : null,
            'ip' => $request->ip(),
            'endpoint' => $request->path(),
            'exceeded_at' => now(),
        ]);
    }
}
