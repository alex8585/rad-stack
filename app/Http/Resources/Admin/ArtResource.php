<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * @property User $resource
 */
class ArtResource extends JsonResource
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
        $img = $this->resource->getMedia('images')->first();

        $thumb = null;
        if ($img) {
            $thumb = $img->getUrl('thumb');
        }

        return [
            /* 'can_be_updated' => Auth::user()->canUpdate($this->resource), */
            'thumb' => $thumb,
            /* 'can_be_impersonated' => Auth::user()->canImpersonate($this->resource), */
        ] + $this->resource->toArray();
    }
}
