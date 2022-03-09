<?php

namespace App\Http\Livewire;

use App\Enums\CourseStatus;
use App\Models\AwardingBody;
use App\Models\Course;
use App\Models\CourseLevel;
use App\Models\ExamSession;
use Livewire\Component;

class CoursesGrid extends Component
{
    public AwardingBody $awardingBody;
    public CourseLevel $courseLevel;
    public ExamSession $examSession;
    public CourseStatus $courseStatus;

    protected $filters = [];

    public $courses;

    protected $listeners = [
        'awardingBody',
        'courseLevel',
        'examSession',
        'courseStatus',
    ];

    public function awardingBody(AwardingBody $awardingBody)
    {
        $this->awardingBody = $awardingBody;
        $this->filters['awardingBody'] = $awardingBody;
        $this->filter();
    }

    public function courseLevel(CourseLevel $courseLevel)
    {
        $this->courseLevel = $courseLevel;
        $this->filters['courseLevel'] = $courseLevel;
        $this->filter();
    }

    public function examSession(ExamSession $examSession)
    {
        $this->examSession = $examSession;
        $this->filters['examSession'] = $examSession;
        $this->filter();
    }

    public function courseStatus($courseStatus)
    {
        $this->courseStatus = CourseStatus::from($courseStatus);
        $this->filters['courseStatus'] = $courseStatus;
        $this->filter();
    }

    public function mount()
    {
        $this->courses = Course::where('exam_session_id', ExamSession::latest()->get()->first()->getKey())->where('status', CourseStatus::Open)->get();
    }

    public function filter()
    {
        $this->courses = Course::latest()->gridFilter($this->filters)->get();
    }

    public function render()
    {
        return view('livewire.courses-grid')
            ->with('courses', $this->courses);
    }
}
