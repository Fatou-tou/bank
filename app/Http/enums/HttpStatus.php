<?php

namespace App\Http\Enums;

enum HttpStatus: int
{
    // Requête réussie (GET, PATCH, DELETE)
    case OK = 200;

    // Ressource créée (POST)
    case CREATED = 201;

    // Suppression réussie sans retour de données
    case NO_CONTENT = 204;

    // Données invalides
    case BAD_REQUEST = 400;

    // Non authentifié
    case UNAUTHORIZED = 401;

    // Non autorisé
    case FORBIDDEN = 403;

    // Ressource inexistante
    case NOT_FOUND = 404;

    // Conflit (ex: compte déjà existant)
    case CONFLICT = 409;

    // Erreur de validation métier
    case UNPROCESSABLE_ENTITY = 422;

    // Limite de débit dépassée
    case TOO_MANY_REQUESTS = 429;

    // Erreur serveur
    case INTERNAL_SERVER_ERROR = 500;

    // Service temporairement indisponible
    case SERVICE_UNAVAILABLE = 503;

    public function getMessage(): string
    {
        return match ($this) {
            self::OK => 'OK', // OK
            self::CREATED => 'Créé', // Created
            self::NO_CONTENT => 'Aucun contenu', // No Content
            self::BAD_REQUEST => 'Mauvaise requête', // Bad Request
            self::UNAUTHORIZED => 'Non autorisé', // Unauthorized
            self::FORBIDDEN => 'Interdit', // Forbidden
            self::NOT_FOUND => 'Non trouvé', // Not Found
            self::CONFLICT => 'Conflit', // Conflict
            self::UNPROCESSABLE_ENTITY => 'Entité non traitable', // Unprocessable Entity
            self::TOO_MANY_REQUESTS => 'Trop de requêtes', // Too Many Requests
            self::INTERNAL_SERVER_ERROR => 'Erreur interne du serveur', // Internal Server Error
            self::SERVICE_UNAVAILABLE => 'Service indisponible', // Service Unavailable
        };
    }
}