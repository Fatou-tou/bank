<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasAdvancedQueryScopes
{
    /**
     * Colonnes autorisées pour la recherche (à définir dans le modèle)
     */
    protected function getSearchableColumns(): array
    {
        return [];
    }

    /**
     * Colonnes autorisées pour le tri (à définir dans le modèle)
     */
    protected function getSortableColumns(): array
    {
        return ['created_at'];
    }

    /**
     * Colonnes autorisées pour le filtrage (à définir dans le modèle)
     */
    protected function getFilterableColumns(): array
    {
        return $this->fillable ?? [];
    }

    /**
     * Recherche globale sur plusieurs colonnes
     */
    public function scopeSearch(Builder $query, ?string $term, array $columns = [])
    {
        $allowedColumns = $columns ?: $this->getSearchableColumns();
        if (!empty($term) && !empty($allowedColumns)) {
            $query->where(function ($q) use ($term, $allowedColumns) {
                foreach ($allowedColumns as $col) {
                    if (strpos($col, '.') !== false) {
                        // Gestion des relations (ex: client.full_name)
                        [$relation, $column] = explode('.', $col, 2);
                        $q->orWhereHas($relation, function ($relQuery) use ($column, $term) {
                            $relQuery->where($column, 'LIKE', "%{$term}%");
                        });
                    } else {
                        $q->orWhere($col, 'LIKE', "%{$term}%");
                    }
                }
            });
        }
        return $query;
    }


    /**
     * Tri dynamique
     */
    public function scopeSortBy(Builder $query, ?string $sortBy = 'created_at', ?string $order = 'desc', array $allowed = [])
    {
        $allowedColumns = $allowed ?: $this->getSortableColumns();
        if (!empty($sortBy) && in_array($sortBy, $allowedColumns)) {
            $query->orderBy($sortBy, $order === 'desc' ? 'desc' : 'asc');
        }
        return $query;
    }

    /**
     * Pagination standardisée
     */
    public function scopePaginateIfNeeded(Builder $query, ?int $perPage = 10)
    {
        return $query->paginate($perPage);
    }

    /**
     * Filtrage dynamique par champ
     */
    public function scopeFilter(Builder $query, array $filters = [])
{
    $allowedColumns = $this->getFilterableColumns();
    foreach ($filters as $key => $value) {
        if (!is_null($value) && in_array($key, $allowedColumns)) {
            if (is_array($value)) {
                $query->whereIn($key, $value);
            } else {
                $query->where($key, $value);
            }
        }
    }
    return $query;
}

}
