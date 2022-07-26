<?php

namespace App\Http\Requests;

use App\Models\Section;
use Illuminate\Foundation\Http\FormRequest;

class StoreEnrollmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $section = Section::findOrFail(request('section'));

        if (! $this->user()->isStudent()) {
            abort(403, __('You are not a student.'));
        }

        if ($section->isFull()) {
            abort(403, __('This section is currently full.'));
        }

        if ($this->user()->isEnrolledInCourse($section->course)) {
            abort(403, __('You are already enrolled in this course.'));
        }

        if ($this->user()->isEnrolledInSection($section)) {
            abort(403, __('You are already enrolled in this section.'));
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
