<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VariantLectureResource extends JsonResource
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
            'courseVariant' => new CourseVariantResource($this->whenLoaded('courseVariant')),
            'day' => $this->day->name,
            'start_time' => $this->start_time->format('h:i A'),
            'end_time' => $this->end_time->format('h:i A'),
            'duration' => $this->duration,
        ];
    }
}
