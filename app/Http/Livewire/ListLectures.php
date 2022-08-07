<?php

namespace App\Http\Livewire;

use App\Models\Enrollment;
use App\Models\Lecture;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ListLectures extends Component implements HasTable
{
    use InteractsWithTable;

    public Enrollment $enrollment;

    public function mount(): void
    {
        $this->enrollment = request('enrollment');
    }

    protected function getTableQuery(): Builder
    {
        return Lecture::query()->whereBelongsTo($this->enrollment->section)->with('recordings');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('start_time')->label('Start TIme')->dateTime('l, d F Y h:i A')->sortable(),
            TextColumn::make('end_time')->label('End Time')->dateTime('l, d F Y h:i A')->sortable(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('viewRecording')
                ->label('View Recording')
                ->icon('heroicon-s-video-camera')
                ->url(fn (Lecture $record) => $record->recordings()->first()->file_url)
                ->openUrlInNewTab()
                ->visible(fn (Lecture $record): bool => $record->recordings->isNotEmpty() && $this->enrollment->attendedLecture($record)),
            Action::make('requestRecording')
                ->label('Request Recording')
                ->icon('heroicon-s-video-camera')
                ->visible(fn (Lecture $record): bool => $record->recordings->isNotEmpty() && ! $this->enrollment->attendedLecture($record))
                ->requiresConfirmation()
                ->modalSubheading('Once you request this recording, you will have a full lecture\'s cost added to your pending invoice. Would you like to continue?')
                ->modalButton('Request')
                ->action(function (Lecture $record) {
                    $record->attendances()->create([
                        'enrollment_id' => $this->enrollment->id,
                        'lecture_id' => $record->id,
                        'join_time' => $record->start_time,
                        'leave_time' => $record->end_time,
                        'duration' => $record->duration,
                    ]);

                    Notification::make()
                        ->title('Recording Granted')
                        ->body('You may access it through this page or on Microsoft Teams.')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'start_time';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'asc';
    }

    public function isTableSearchable(): bool
    {
        return true;
    }

    protected function applySearchToTableQuery(Builder $query): Builder
    {
        if (filled($searchQuery = $this->getTableSearchQuery())) {
            $query->whereIn('id', Lecture::search($searchQuery)->keys());
        }

        return $query;
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-clock';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No Lectures Yet';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Your tutor hasn\'t added any lectures yet. Check back later!';
    }

    public function render()
    {
        return view('livewire.list-lectures');
    }
}
