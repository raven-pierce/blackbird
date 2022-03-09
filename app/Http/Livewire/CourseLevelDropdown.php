<?php

namespace App\Http\Livewire;

use App\Models\CourseLevel;
use Livewire\Component;

class CourseLevelDropdown extends Component
{
    public bool $showLinks = false;

    public CourseLevel|null $currentCourseLevel = null;

    public function select(CourseLevel $courseLevel)
    {
        $this->showLinks           = false;
        $this->currentCourseLevel = $courseLevel;
        $this->emit('courseLevel', $this->currentCourseLevel);
    }

    public function render()
    {
        return view('livewire.course-level-dropdown')
            ->with('course_levels', CourseLevel::all());
    }
}
