<?php

namespace App\Http\Queries;

use App\Http\Resources\Admin\TagResource;
use App\Models\Tag;
use App\Support\GlobalSearchFilter;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TagQuery extends BaseQuery
{
    protected int $perPage = 20;

    public function __construct()
    {
        $direction = request()->get('descending', 0) ? 'ASC' : 'DESC';
        $sort = request()->get('sortBy', 'id');

        $this->query = QueryBuilder::for(Tag::class)
            ->allowedFilters([
                AllowedFilter::custom('q', new GlobalSearchFilter(['name'])),
                AllowedFilter::partial('name'),
                AllowedFilter::partial('order_number'),
                AllowedFilter::exact('id'),
            ])
            ->orderBy($sort, $direction)
        ;
        $this->resource = 'tags';
    }

    public function collection(): AnonymousResourceCollection
    {
        return TagResource::collection($this->paginate());
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
