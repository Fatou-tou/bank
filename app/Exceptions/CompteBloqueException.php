<?php

namespace App\Exceptions;

use Exception;

class CompteBloqueException extends Exception
{
    public function __construct($numeroCompte = null, $motif = null)
    {
        $message = $numeroCompte
            ? "Le compte '{$numeroCompte}' est bloqué."
            : "Le compte est bloqué.";

        if ($motif) {
            $message .= " Motif : {$motif}.";
        }

        parent::__construct($message, 403);
    }
}