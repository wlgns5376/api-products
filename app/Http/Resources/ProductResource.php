<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //$this->image = asset('storage/images/'.$this->image);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'stock' => $this->stock,
            'image' => $this->image,
            'image_url' => $this->imageUrl(),
        ];
    }

    /**
     * Get Image url
     * 
     * @return string
     */
    protected function imageUrl()
    {
        if ($this->image && Storage::disk('public')->exists('images/'.$this->image)) {
            return asset('storage/images/'.$this->image);
        }

        return null;
    }
}
