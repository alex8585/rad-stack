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
        $tags = request()->get('tags', null);

        $tagsArr = [];
        if ($tags) {
            $tagsArr = explode(',', $tags);
        }

        $query = QueryBuilder::for(Portfolio::class)
            ->with(['media', 'tags'])
            ->allowedFilters([
                AllowedFilter::custom('q', new GlobalSearchFilter(['name', 'url'])),
                AllowedFilter::partial('name'),
                AllowedFilter::partial('order_number'),
                AllowedFilter::exact('id'),
            ])
            ->orderBy($sort, $direction)
        ;

        $query->when($tagsArr, function ($q) use ($tagsArr) {
            $q->whereHas('tags', function ($query) use ($tagsArr) {
                $query->whereIn('tags.id', $tagsArr);
            });
        });

        $this->query = $query;
        $this->resource = 'portfolios';
    }

    public function collection(): AnonymousResourceCollection
    {
        return PortfolioResource::collection($this->paginate());
    }

    public function get(): array
    {
        $resCollection = $this->collection();

        return [
            'sortBy' => request()->get('sort', 'id'),
            'filter' => request()->get('filter'),
            'items' => $resCollection->items(),
            'perPage' => (int) $resCollection->perPage(),
            'currentPage' => (int) $resCollection->currentPage(),
            'total' => (int) $resCollection->total(),
        ];
    }
}
