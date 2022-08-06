<?php

namespace App\Http\Livewire;

use App\Models\Lecture;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ListLectures extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery(): Builder
    {
        // get lectures for current enrollment
        return Lecture::query()->whereBelongsTo(auth()->user());
    }

    public function render()
    {
        return view('livewire.list-lectures');
    }
}
