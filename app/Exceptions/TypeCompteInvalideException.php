<?php

namespace App\Exceptions;

use Exception;

class TypeCompteInvalideException extends Exception
{
    public function __construct($type = null)
    {
        $typesValides = ['epargne', 'cheque'];
        $message = $type
            ? "Type de compte '{$type}' invalide. Types valides : " . implode(', ', $typesValides) . "."
            : "Type de compte invalide. Types valides : " . implode(', ', $typesValides) . ".";

        parent::__construct($message, 400);
    }
}