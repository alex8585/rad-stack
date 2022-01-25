<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Queries\ArtQuery;
use App\Http\Queries\UserQuery;
use App\Http\Requests\Admin\ArtStoreRequest;
use App\Http\Requests\Admin\ArtUpdateRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\Art;
use App\Models\User;
use Inertia\Inertia;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Put;

#[Prefix('arts')]
class ArtController extends Controller
{
    #[Get('/', name: 'arts')]
    public function index(ArtQuery $artQuery)
    {
        return $artQuery->paginateOrExport(
            fn ($data) => Inertia::render('arts/Index', ['action' => 'list'] + $data)
        );

        /* $data = Art::paginate(5)->toArray(); */

        /* return Inertia::render( */
        /*     'arts/Index', */
        /*     [ */
        /*         'action' => 'list', */
        /*         'items' => $data['data'], */
        /*     ] */
        /* ); */

        /* return app(UserQuery::class)->make() */
        /*     ->paginateOrExport(fn ($data) => Inertia::render('users/Index', ['action' => 'list'] + $data)) */
        /* ; */
    }

    #[Get('{user}', name: 'users.show')]
    public function show(User $user)
    {
        return Inertia::render('users/Index', [
            'action' => 'show',
            'user' => UserResource::make($user),
        ] + app(UserQuery::class)->make()->get());
    }

    #[Post('/', name: 'users.store')]
    public function store(ArtStoreRequest $request)
    {
        /* dd($request); */
        /* dd($request->files->get('files')); */
        $art = Art::create($request->except(['file', 'files']));

        /* dd($request->files->get('files')); */

        foreach ($request->files->get('files') as $fileArr) {
            $file = $fileArr['file'];
            $art->addMedia($file)->toMediaCollection('images');
        }
        /* $art->addMediaFromRequest($request->files)->toMediaCollection('images'); */

        /* foreach($request->files as $filesArr) { */
        /*     foreach($filesArr as $fileArr){ */
        /*         /1* dd($fileArr); *1/ */
        /*         $file = $fileArr['file']; */
        /*         /1* dd($file); *1/ */
        /*     $art->addMedia($file)->toMediaCollection('images'); */
        /*     } */
        /* } */

        return back()->with('success', 'The art has been stored');
    }

    #[Put('{art}', name: 'arts.update') ]
    public function update(Art $art, ArtUpdateRequest $request)
    {
        $art->update($request->all());

        return back()->with('success', 'The art has been updated');
    }

    #[Delete('{art}', name: 'arts.destroy') ]
    public function destroy(Art $art)
    {
        $art->delete();

        return back()->with('success', 'The art has been deleted');
    }
}
