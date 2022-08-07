<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnrollmentRequest;
use App\Models\Enrollment;
use App\Models\Section;
use NumberFormatter;

class EnrollmentController extends Controller
{
    public function index()
    {
        return view('enrollments.index', [
            'enrollments' => auth()->user()->enrollments,
            'spellOutFormatter' => NumberFormatter::create('en-US', NumberFormatter::SPELLOUT),
        ]);
    }

    public function show(Enrollment $enrollment)
    {
        return view('enrollments.show', [
            'enrollment' => $enrollment->load('attendances'),
            'spellOutFormatter' => NumberFormatter::create('en-US', NumberFormatter::SPELLOUT),
        ]);
    }

    public function store(StoreEnrollmentRequest $request)
    {
        $request->user()->enrollInSection(Section::findOrFail($request->section));

        // You've been enrolled in this course.

        return redirect()->route('enrollments.index');
    }

    public function destroy(Enrollment $enrollment)
    {
        if (auth()->user()->enrollments->contains($enrollment)) {
            $enrollment->deleteOrFail();

            // You've been withdrawn from this course.

            return redirect()->route('enrollments.index');
        }

        // You're not currently enrolled in this course.

        return redirect()->route('dashboard');
    }
}
