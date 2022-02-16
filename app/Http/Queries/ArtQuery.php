<?php

namespace App\Http\Queries;

use App\Http\Resources\Admin\ArtResource;
use App\Models\Art;
use App\Support\GlobalScoutSearch;
use App\Support\GlobalSearchFilter;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ArtQuery extends BaseQuery
{
    protected int $perPage = 5;

    public function __construct()
    {
        $this->resource = 'arts';
        $direction = request()->get('descending', 0) ? 'ASC' : 'DESC';
        $sort = request()->get('sortBy', 'id');

        $this->query = QueryBuilder::for(Art::class)
            ->with('media')
            ->allowedFilters([
                AllowedFilter::custom('q', new GlobalScoutSearch(Art::class)),
                /* AllowedFilter::custom('q', new GlobalSearchFilter(['title', 'description'])), */
                AllowedFilter::partial('title'),
                AllowedFilter::partial('description'),
                AllowedFilter::exact('id'),
            ])
            ->orderBy($sort, $direction)
        ;
    }

    public function collection(): AnonymousResourceCollection
    {
        return ArtResource::collection($this->paginate());
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
