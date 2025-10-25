<?php

namespace App\Exceptions;

use Exception;

class RateLimitExceededException extends Exception
{
    public function __construct($retryAfter = null, $endpoint = null)
    {
        $message = "Trop de requêtes.";

        if ($endpoint) {
            $message .= " Endpoint : {$endpoint}.";
        }

        if ($retryAfter) {
            $message .= " Réessayez dans {$retryAfter} secondes.";
        }

        parent::__construct($message, 429);
    }
}