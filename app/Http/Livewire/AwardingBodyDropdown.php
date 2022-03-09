<?php

namespace App\Http\Livewire;

use App\Enums\CourseStatus;
use App\Models\AwardingBody;
use App\Models\Course;
use App\Models\CourseLevel;
use App\Models\ExamSession;
use Livewire\Component;

class AwardingBodyDropdown extends Component
{
    public bool $showLinks = false;

    public AwardingBody|null $currentAwardingBody = null;

    public function select(AwardingBody $awardingBody)
    {
        $this->showLinks           = false;
        $this->currentAwardingBody = $awardingBody;
        $this->emit('awardingBody', $this->currentAwardingBody);
    }

    public function render()
    {
        return view('livewire.awarding-body-dropdown')
            ->with('awarding_bodies', AwardingBody::all());
    }
}
