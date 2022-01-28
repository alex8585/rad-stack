<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Queries\PortfolioQuery;
use Spatie\RouteAttributes\Attributes\Get;

class PortfoliosController extends Controller
{
    public function __construct()
    {
    }

    #[Get('portfolios')]
    public function index(PortfolioQuery $query)
    {
        return $query->paginateOrExport(
            fn ($data) => $data
        );
    }
}
