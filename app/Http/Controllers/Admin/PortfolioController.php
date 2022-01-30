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
        /* dd($request->all()); */
        $portfolio = Portfolio::create($request->except(['files']));
        $files = $request->files->get('files');
        if ($files) {
            foreach ($files as $file) {
                $portfolio->addMedia($file)->toMediaCollection('images');
            }
        }

        return back()->with('success', 'The portfolio has been stored');
    }

    #[Post('{portfolio}', name: 'portfolio.update') ]
    public function update(Portfolio $portfolio, PortfolioUpdateRequest $request)
    {
        $portfolio->update($request->except(['files']));

        $portfolio->clearMediaCollection('images');

        $files = $request->files->get('files');
        if ($files) {
            foreach ($files as $file) {
                $portfolio->addMedia($file)->toMediaCollection('images');
            }
        }

        return back()->with('success', 'The portfolio has been updated');
    }

    #[Delete('{portfolio}', name: 'portfolio.destroy') ]
    public function destroy(Portfolio $portfolio)
    {
        $portfolio->delete();

        return back()->with('success', 'The portfolio has been deleted');
    }
}
