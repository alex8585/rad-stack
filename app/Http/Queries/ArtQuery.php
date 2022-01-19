<?php

namespace App\Http\Queries;

use App\Http\Resources\Admin\ArtResource;
use App\Models\Art;
use App\Support\GlobalSearchFilter;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ArtQuery extends BaseQuery
{
    protected int $perPage = 5;

    public function __construct()
    {
        $direction = request()->get('descending', 0) ? 'ASC' : 'DESC';
        $sort = request()->get('sortBy', 'id');

        $this->query = QueryBuilder::for(Art::class)
            ->allowedFilters([
                AllowedFilter::custom('q', new GlobalSearchFilter(['name', 'email'])),
                AllowedFilter::partial('name'),
                AllowedFilter::partial('email'),
                AllowedFilter::exact('id'),
                AllowedFilter::exact('role'),
                AllowedFilter::exact('active'),
            ])
            ->orderBy($sort, $direction)
        ;
        $this->resource = 'arts';
    }

    public function collection(): AnonymousResourceCollection
    {
        return ArtResource::collection($this->paginate());
    }

    public function get(): array
    {
        /* dd($this->collection()); */
        return [
            'sortBy' => request()->get('sort', 'id'),
            'filter' => request()->get('filter'),
            'items' => $this->collection()->items(),
            'perPage' => (int) $this->collection()->perPage(),
            'currentPage' => (int) $this->collection()->currentPage(),
            'total' => (int) $this->collection()->total(),
        ];
    }
}
