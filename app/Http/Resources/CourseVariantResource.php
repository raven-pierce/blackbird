<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseVariantResource extends JsonResource
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
            'course' => $this->course->toResource(),
            'seats' => $this->seats,
            'delivery_method' => $this->delivery_method->name,
            'variant_lectures' => VariantLectureResource::collection($this->variantLectures),
        ];
    }
}
