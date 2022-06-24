<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Resources\EnrollmentCollection;
use App\Http\Resources\EnrollmentResource;
use App\Models\CourseVariant;
use App\Models\Enrollment;
use Inertia\Inertia;

class EnrollmentController extends Controller
{
    public function index()
    {
        //return EnrollmentCollection::make(auth()->user()->attendsCourses);
        return Inertia::render('Enrollment/Index', [
            'enrollments' => EnrollmentCollection::make(auth()->user()->attendsCourses),
        ]);
    }

    public function show(Enrollment $enrollment)
    {
        return Inertia::render('Enrollment/Show', [
            'enrollment' => EnrollmentResource::make($enrollment),
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
