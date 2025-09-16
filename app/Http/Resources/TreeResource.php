<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TreeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'user_id' => $this->user_id,
            'tree_name_id' => $this->tree_name_id,
            'ward' => $this->ward,
            'tree_no' => $this->tree_no,
            'tree_name' => $this->tree_name,
            'scientific_name' => $this->scientific_name,
            'family' => $this->family,
            'girth_cm' => $this->girth_cm,
            'height_m' => $this->height_m,
            'canopy_m' => $this->canopy_m,
            'age' => $this->age,
            'condition' => $this->condition,
            'address' => $this->address,
            'landmark' => $this->landmark,
            'ownership' => $this->ownership,
            'concern_person_name' => $this->concern_person_name,
            'remark' => $this->remark,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'accuracy' => $this->accuracy,
            'continue' => $this->continue,
            'photo' => $this->photo,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Add related photos if needed
            'photos' => $this->whenLoaded('photos'),
        ];
    }
}
