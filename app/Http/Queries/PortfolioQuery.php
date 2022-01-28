<?php

namespace App\Http\Queries;

use App\Http\Resources\Admin\PortfolioResource;
use App\Models\Portfolio;
use App\Support\GlobalSearchFilter;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PortfolioQuery extends BaseQuery
{
    protected int $perPage = 5;

    public function __construct()
    {
        $direction = request()->get('descending', 0) ? 'ASC' : 'DESC';
        $sort = request()->get('sortBy', 'id');

        $this->query = QueryBuilder::for(Portfolio::class)
            ->allowedFilters([
                AllowedFilter::custom('q', new GlobalSearchFilter(['name'])),
                AllowedFilter::partial('name'),
                AllowedFilter::exact('id'),
            ])
            ->orderBy($sort, $direction)
        ;
        $this->resource = 'portfolios';
    }

    public function collection(): AnonymousResourceCollection
    {
        return PortfolioResource::collection($this->paginate());
    }

    public function get(): array
    {
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
