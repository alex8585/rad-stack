<?php

namespace App\Http\Queries;

use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use App\Support\GlobalSearchFilter;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserQuery extends BaseQuery
{
    protected int $perPage = 5;

    public function __construct()
    {
        $direction = request()->get('descending', 0) ? 'ASC' : 'DESC';
        $sort = request()->get('sortBy', 'id');

        $this->query = QueryBuilder::for(User::class)
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
        $this->resource = 'users';
    }

    public function collection(): AnonymousResourceCollection
    {
        return UserResource::collection($this->paginate());
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
