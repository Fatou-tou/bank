<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CompteCollection extends ResourceCollection
{
    public $collects = CompteResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'success' => true,
            'data' => $this->collection,
            'pagination' => [
                'currentPage' => $this->resource->currentPage(),
                'totalPages' => $this->resource->lastPage(),
                'totalItems' => $this->resource->total(),
                'itemsPerPage' => $this->resource->perPage(),
                'hasNext' => $this->resource->hasMorePages(),
                'hasPrevious' => $this->resource->currentPage() > 1
            ],
            'links' => [
                'self' => $request->url() . '?' . http_build_query(['page' => $this->resource->currentPage(), 'limit' => $this->resource->perPage()]),
                'next' => $this->resource->nextPageUrl(),
                'first' => $request->url() . '?' . http_build_query(['page' => 1, 'limit' => $this->resource->perPage()]),
                'last' => $request->url() . '?' . http_build_query(['page' => $this->resource->lastPage(), 'limit' => $this->resource->perPage()])
            ]
        ];
    }
}