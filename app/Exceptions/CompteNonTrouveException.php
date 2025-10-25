<?php

namespace App\Exceptions;

use Exception;

class CompteNonTrouveException extends Exception
{
    public function __construct($numeroCompte = null)
    {
        $message = $numeroCompte
            ? "Le compte avec le numéro '{$numeroCompte}' n'a pas été trouvé."
            : "Le compte demandé n'a pas été trouvé.";

        parent::__construct($message, 404);
    }
}