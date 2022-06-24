<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use NumberFormatter;

class EnrollmentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $formatter = new NumberFormatter('en', NumberFormatter::SPELLOUT);

        $collection = $this->collection->map(function ($object, $key) use ($formatter) {
            $object->position = ucwords($formatter->format($key + 1));
            return $object;
        });

        $enrollments = EnrollmentResource::collection($collection);

        return [
            'data' => $enrollments,
            'meta' => [
                'enrollment_count' => $enrollments->count()
            ]
        ];
    }
}
