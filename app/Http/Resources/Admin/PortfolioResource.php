<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Tags\Tag;

/**
 * @property Tag $resource
 */
class PortfolioResource extends JsonResource
{
    public static $wrap;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        /* $img = $this->resource->getMedia('images')->first(); */
        $img = $this->resource->media->first();

        $thumb = null;
        $image = null;

        if ($img) {
            $thumb = $img->getUrl('thumb');
            $image = $img->getUrl();
        }

        return collect($this->resource)
            ->except(['media'])
            ->merge([
                'thumb' => $thumb,
                'image' => $image,
            ])
        ;

        return $this->resource->toArray();
    }
}
