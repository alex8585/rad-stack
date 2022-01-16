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
    public function __construct()
    {
        $this->query = QueryBuilder::for(Art::class)
            ->allowedFilters([
                AllowedFilter::custom('q', new GlobalSearchFilter(['name', 'email'])),
                AllowedFilter::partial('name'),
                AllowedFilter::partial('email'),
                AllowedFilter::exact('id'),
                AllowedFilter::exact('role'),
                AllowedFilter::exact('active'),
            ])
            ->allowedSorts(['id', 'name', 'last_login_at', 'created_at', 'updated_at'])
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
            'sort' => request()->get('sort', 'id'),
            'filter' => request()->get('filter'),
            'items' => $this->collection()->items(),
            'perPage' => (int) $this->collection()->perPage(),
            'currentPage' => (int) $this->collection()->currentPage(),
            'total' => (int) $this->collection()->total(),
        ];
    }
}
