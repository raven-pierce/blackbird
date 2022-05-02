<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnrollmentRequest;
use App\Models\CourseVariant;
use App\Models\Enrollment;
use Illuminate\Http\Response;
use Throwable;

class EnrollmentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreEnrollmentRequest  $request
     *
     * @return Response
     */
    public function store(StoreEnrollmentRequest $request)
    {
        $request->user()->enrollInCourseVariant(CourseVariant::findOrFail($request->courseVariant));

        return back()->with('success', __('You have successfully enrolled in this course.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Enrollment  $enrollment
     *
     * @return Response
     * @throws Throwable
     */
    public function destroy(Enrollment $enrollment)
    {
        if (auth()->user()->attendsCourses->contains($enrollment)) {
            $enrollment->deleteOrFail();
            return back(204)->with('success', __('You have successfully been delisted from this course.'));
        }

        abort(403, __('You are not currently enrolled in this course.'));
    }
}
