<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Queries\TagQuery;
use Spatie\RouteAttributes\Attributes\Get;

class TagsController extends Controller
{
    public function __construct()
    {
    }

    #[Get('tags')]
    public function index(TagQuery $tagQuery)
    {
        return $tagQuery->paginateOrExport(
            fn ($data) => $data
        );
    }
}
