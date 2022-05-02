<?php

namespace App\Http\Requests;

use App\Models\CourseVariant;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreAssistantshipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $courseVariant = CourseVariant::findOrFail(request('courseVariant'));
        $user = User::findOrFail(request('assistant'));

        if (! $courseVariant->course->tutor === auth()->user()) {
            abort(403, __('You are not the tutor of this course.'));
        }
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
