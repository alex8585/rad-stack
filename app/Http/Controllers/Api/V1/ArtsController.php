<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Queries\ArtQuery;
use App\Models\Art;
use Spatie\MediaLibrary\Support\ImageFactory;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('arts')]
class ArtsController extends Controller
{
    public function __construct()
    {
    }

    #[Get('/')]
    public function index(ArtQuery $artQuery)
    {
        return $artQuery->paginateOrExport(
            fn ($data) => $data
        );
    }

    #[Get('/ids') ]
    public function getArtsIds()
    {
        return Art::pluck('id');
    }

    #[Get('{art}') ]
    public function getArts(Art $art)
    {
        $art->load('media');

        $images = $art->getMedia('images');
        $thumbs = [];
        $media = [];
        foreach ($images as $img) {
            $url = url($img->getUrl());

            $imageInstance = ImageFactory::load($url);
            $thumbs[] = [
                'img' => $img->getUrl('thumb'),
                'id' => $img->id,
            ];
            $media[] = [
                'id' => $img->id,
                'name' => $img->file_name,
                'url' => $url,
                'width' => $imageInstance->getWidth(),
                'height' => $imageInstance->getHeight(),
            ];
        }
        $art['thumbs'] = $thumbs;
        $art['images'] = $media;

        return $art;
    }
}
