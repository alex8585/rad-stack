<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class GlobalScoutSearch implements Filter
{
    private $modelClass;

    public function __construct($modelClass)
    {
        $this->modelClass = $modelClass;
    }

    public function __invoke(Builder $query, $value, string $property)
    {
        if ($value) {
            /* $idS = $this->modelClass::search($value)->get()->pluck('id'); */
            $rawScout = $this->modelClass::search($value)->raw();
            if ($rawScout['hits']) {
                $idS = collect($rawScout['hits'])->pluck('id')->toArray();

                return $query->whereIn('id', $idS);
            }

            return $query->whereRaw('0 = 1');
        }

        return $query;
    }
}
