<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Queries\TagQuery;
use App\Http\Requests\Admin\TagStoreRequest;
use App\Http\Requests\Admin\TagUpdateRequest;
use App\Models\Tag;
use Inertia\Inertia;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('tags')]
class TagController extends Controller
{
    #[Get('/', name: 'tags')]
    public function index(TagQuery $query)
    {
        return $query->paginateOrExport(
            fn ($data) => Inertia::render('tags/Index', $data)
        );
    }

    #[Post('/', name: 'tags.store')]
    public function store(TagStoreRequest $request)
    {
        Tag::create($request->all());

        return back()->with('success', 'The tag has been stored');
    }

    #[Post('{tag}', name: 'tags.update') ]
    public function update(Tag $tag, TagUpdateRequest $request)
    {
        $tag->update($request->all());

        return back()->with('success', 'The tag has been updated');
    }

    #[Delete('{tag}', name: 'tags.destroy') ]
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return back()->with('success', 'The tag has been deleted');
    }
}
