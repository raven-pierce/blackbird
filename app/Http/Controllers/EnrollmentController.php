<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Resources\EnrollmentResource;
use App\Models\CourseVariant;
use App\Models\Enrollment;

class EnrollmentController extends Controller
{

    public function index()
    {
        return view('enrollments.index', [
            'enrollments' => auth()->user()->attendsCourses,
        ]);
    }

    public function show(Enrollment $enrollment)
    {
        return view('enrollments.show', [
            'enrollment' => $enrollment,
        ]);
    }

    public function store(StoreEnrollmentRequest $request)
    {
        $request->user()->enrollInCourseVariant(CourseVariant::findOrFail($request->courseVariant));

        // You've been enrolled in this course.

        return redirect()->route('enrollments.index');
    }

    public function destroy(Enrollment $enrollment)
    {
        if (auth()->user()->attendsCourses->contains($enrollment)) {
            $enrollment->deleteOrFail();

            // You've been withdrawn from this course.

            return redirect()->route('enrollments.index');
        }

        // You're not currently enrolled in this course.

        return redirect()->route('dashboard');
    }
}
