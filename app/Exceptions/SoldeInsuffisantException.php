<?php

namespace App\Exceptions;

use Exception;

class SoldeInsuffisantException extends Exception
{
    public function __construct($soldeActuel, $montantDemande)
    {
        $message = "Solde insuffisant. Solde actuel : {$soldeActuel}, montant demandé : {$montantDemande}.";

        parent::__construct($message, 400);
    }
}