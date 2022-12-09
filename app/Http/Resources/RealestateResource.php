<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
class RealestateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $images = array();
        foreach($this->images as $image)
            $images[] = url($image);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'images' => $images,
            'description' => $this->description,
            'status' => $this->status,
            'featred' => $this->featred,
            'type' => $this->type,
            'size' => $this->size,
            'bedrooms' => $this->bedrooms,
            'bethrooms' => $this->bethrooms,
            'price_sell' => $this->price_sell,
            'price_rent' => $this->price_rent,
            'localisation' => $this->localisation,
            'tags' => $this->tags,
        ];
    }
}
