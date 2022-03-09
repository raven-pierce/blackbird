<?php

namespace App\Http\Livewire;

use App\Enums\CourseStatus;
use Livewire\Component;

class CourseStatusDropdown extends Component
{
    public bool $showLinks = false;

    public CourseStatus $currentCourseStatus = CourseStatus::Open;

    public function mount()
    {
        $this->emit('courseStatus', $this->currentCourseStatus);
    }

    public function select($courseStatus)
    {
        $this->showLinks           = false;
        $this->currentCourseStatus = CourseStatus::from($courseStatus);
        $this->emit('courseStatus', $this->currentCourseStatus);
    }

    public function render()
    {
        return view('livewire.course-status-dropdown')
            ->with('course_statuses', CourseStatus::cases());
    }
}
