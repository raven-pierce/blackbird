<?php

namespace App\Filament\Resources\LectureResource\Pages;

use App\Filament\Resources\LectureResource;
use App\Jobs\SyncLectureRecordings;
use App\Models\Lecture;
use Filament\Forms\Components\DateTimePicker;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLectures extends ListRecords
{
    protected static string $resource = LectureResource::class;

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
        return 'Here you can create, delete, and generate lectures for your sections.';
    }

    protected function getActions(): array
    {
        return [
            Action::make()
                ->label('Sync Recordings')
                ->action(fn (array $data) => Lecture::query()
                    ->whereBetween('start_time', [$data['from_date'], $data['to_date']])
                    ->each(fn (Lecture $lecture) => SyncLectureRecordings::dispatch($lecture)))
                ->requiresConfirmation()
                ->modalSubheading('Once you select a date range, all lectures that took place within it (inclusive) will be synced locally. Would you like to continue?')
                ->modalButton('Sync')
                ->form([
                    DateTimePicker::make('from_date')
                        ->label('From')
                        ->displayFormat('l, d F Y h:i A')
                        ->required(),
                    DateTimePicker::make('to_date')
                        ->label('To')
                        ->displayFormat('l, d F Y h:i A')
                        ->required(),
                ]),
            CreateAction::make()->label('New Lecture'),
        ];
    }
}
