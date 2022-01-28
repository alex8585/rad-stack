<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Queries\ArtQuery;
use Spatie\RouteAttributes\Attributes\Get;

class ArtsController extends Controller
{
    public function __construct()
    {
    }

    #[Get('arts')]
    public function index(ArtQuery $artQuery)
    {
        return $artQuery->paginateOrExport(
            fn ($data) => $data
        );
    }
}
