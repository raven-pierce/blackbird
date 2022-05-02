<?php

namespace App\Http\Requests;

use App\Models\CourseVariant;
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
        $courseVariant = CourseVariant::findOrFail(request('courseVariant'));

        if (! $this->user()->isStudent()) {
            abort(403, __('You are not a student.'));
        }

        if ($courseVariant->isFull()) {
            abort(403, __('This course group is currently full.'));
        }

        if ($this->user()->isEnrolledInCourse($courseVariant->course)) {
            abort(403, __('You are already enrolled in this course.'));
        }

        if ($this->user()->isEnrolledInCourseVariant($courseVariant)) {
            abort(403, __('You are already enrolled in this course group.'));
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
