<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnrollmentRequest;
use App\Models\CourseVariant;
use App\Models\Enrollment;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Throwable;

class EnrollmentController extends Controller
{
    public function index()
    {
        return Inertia::render('Enrollment/Index', [
            'enrollments' => auth()->user()->attendsCourses
        ]);
    }

    public function store(StoreEnrollmentRequest $request)
    {
        $request->user()->enrollInCourseVariant(CourseVariant::findOrFail($request->courseVariant));

        return back()->with('success', __('You have successfully enrolled in this course.'));
    }

    public function destroy(Enrollment $enrollment)
    {
        if (auth()->user()->attendsCourses->contains($enrollment)) {
            $enrollment->deleteOrFail();
            return back(204)->with('success', __('You have successfully been delisted from this course.'));
        }

        abort(403, __('You are not currently enrolled in this course.'));
    }
}
