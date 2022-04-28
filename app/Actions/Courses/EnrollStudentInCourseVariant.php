<?php

namespace App\Actions\Courses;

use App\Models\CourseVariant;
use App\Models\User;
use Spatie\QueueableAction\QueueableAction;

class EnrollStudentInCourseVariant
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

        if ($courseVariant->students->count() >= $courseVariant->seats) {
            abort(403, __('This course group is currently full.'));
        }

        if ($courseVariant->students->contains($user)) {
            abort(403, __('You may not enroll to two groups within the same course.'));
        }

        $courseVariant->students()->attach($user);

        session()->flash('success', __('You have successfully enrolled in this course.'));

        return $user->attendsCourses;
    }
}
