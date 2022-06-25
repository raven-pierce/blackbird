<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use NumberFormatter;

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
        $formatter = new NumberFormatter('en', NumberFormatter::SPELLOUT);

        return [
            'id' => $this->id,
            'course' => $this->course->toResource(),
            'seats' => $this->seats,
            'delivery_method' => $this->delivery_method->name,
            'lecture_count' => $formatter->format($this->resource->variantLectures->count()),
            'variant_lectures' => VariantLectureResource::collection($this->variantLectures),
        ];
    }
}
