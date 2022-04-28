<?php

namespace App\Actions\Courses;

use App\Models\CourseVariant;
use App\Models\User;
use Spatie\QueueableAction\QueueableAction;

class DelistStudentFromCourseVariant
{
    use QueueableAction;

    public function __construct()
    {

    }

    public function execute(CourseVariant $courseVariant, User $user)
    {
        if (! $user->isStudent()) {
            abort(403, __('You are not a student.'));
        }

        if (! $courseVariant->students->contains($user)) {
            abort(403, __('You are not currently enrolled in this course group.'));
        }

        $courseVariant->students()->detach($user);

        session()->flash('success', __('You have been successfully delisted from this course.'));

        return $user->attendsCourses;
    }
}
