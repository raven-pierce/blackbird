<?php

namespace App\Http\Livewire;

use App\Models\ExamSession;
use Livewire\Component;

class ExamSessionDropdown extends Component
{
    public bool $showLinks = false;

    public ExamSession|null $currentExamSession;

    public function mount()
    {
        $this->currentExamSession = ExamSession::latest()->get()->first();
        $this->emit('examSession', $this->currentExamSession);
    }

    public function select(ExamSession $examSession)
    {
        $this->showLinks           = false;
        $this->currentExamSession = $examSession;
        $this->emit('examSession', $this->currentExamSession);
    }

    public function render()
    {
        return view('livewire.exam-session-dropdown')
            ->with('exam_sessions', ExamSession::all());
    }
}
