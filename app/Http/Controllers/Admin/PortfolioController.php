<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Queries\PortfolioQuery;
use App\Http\Requests\Admin\PortfolioStoreRequest;
use App\Http\Requests\Admin\PortfolioUpdateRequest;
use App\Models\Portfolio;
use Inertia\Inertia;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('portfolios')]
class PortfolioController extends Controller
{
    #[Get('/', name: 'portfolios')]
    public function index(PortfolioQuery $query)
    {
        return $query->paginateOrExport(
            fn ($data) => Inertia::render('portfolios/Index', $data)
        );
    }

    #[Post('/', name: 'portfolio.store')]
    public function store(PortfolioStoreRequest $request)
    {
        Portfolio::create($request->all());

        return back()->with('success', 'The portfolio has been stored');
    }

    #[Post('{portfolio}', name: 'portfolio.update') ]
    public function update(Portfolio $portfolio, PortfolioUpdateRequest $request)
    {
        $portfolio->update($request->all());

        return back()->with('success', 'The portfolio has been updated');
    }

    #[Delete('{portfolio}', name: 'portfolio.destroy') ]
    public function destroy(Portfolio $portfolio)
    {
        $portfolio->delete();

        return back()->with('success', 'The portfolio has been deleted');
    }
}
