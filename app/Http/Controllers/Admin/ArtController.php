<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Queries\ArtQuery;
use App\Http\Requests\Admin\ArtStoreRequest;
use App\Http\Requests\Admin\ArtUpdateRequest;
use App\Models\Art;
use Inertia\Inertia;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('arts')]
class ArtController extends Controller
{
    #[Get('/', name: 'arts')]
    public function index(ArtQuery $artQuery)
    {
        return $artQuery->paginateOrExport(
            fn ($data) => Inertia::render('arts/Index', ['action' => 'list'] + $data)
        );
    }

    #[Post('/', name: 'arts.store')]
    public function store(ArtStoreRequest $request)
    {
        /* dd($request); */
        /* dd($request->files->get('files')); */
        $art = Art::create($request->except(['file', 'files']));

        $files = $request->files->get('files');
        if ($files) {
            foreach ($files as $file) {
                $art->addMedia($file)->toMediaCollection('images');
            }
        }

        return back()->with('success', 'The art has been stored');
    }

    #[Post('{art}', name: 'arts.update') ]
    public function update(Art $art, ArtUpdateRequest $request)
    {
        $art->update($request->except(['file', 'files']));

        $art->clearMediaCollection('images');

        /* dd($request->files->get('files')); */
        $files = $request->files->get('files');
        if ($files) {
            foreach ($files as $file) {
                $art->addMedia($file)->toMediaCollection('images');
            }
        }

        return back()->with('success', 'The art has been updated');
    }

    #[Delete('{art}', name: 'arts.destroy') ]
    public function destroy(Art $art)
    {
        $art->delete();

        return back()->with('success', 'The art has been deleted');
    }
}
