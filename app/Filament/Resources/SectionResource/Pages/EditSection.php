<?php

namespace App\Filament\Resources\SectionResource\Pages;

use App\Filament\Resources\SectionResource;
use App\Jobs\RunReport;
use App\Models\Enrollment;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\DeleteAction;
use Filament\Pages\Actions\ForceDeleteAction;
use Filament\Pages\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditSection extends EditRecord
{
    protected static string $resource = SectionResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('runReports')
                ->label('Run Reports')
                ->action(fn (array $data) => $this->record->enrollments->each(fn (Enrollment $enrollment) => RunReport::dispatch($enrollment, Carbon::parse($data['start_date']), Carbon::parse($data['end_date']))))
                ->requiresConfirmation()
                ->modalButton('Run Reports')
                ->form([
                    DateTimePicker::make('start_date')
                        ->label('From')
                        ->displayFormat('l, d F Y')
                        ->withoutSeconds()
                        ->required(),
                    DateTimePicker::make('end_date')
                        ->label('To')
                        ->displayFormat('l, d F Y')
                        ->withoutSeconds()
                        ->required(),
                ]),
            Action::make('generateLectures')
                ->label('Generate Lectures')
                ->action(fn (array $data) => $this->record->generateLectures($data['day'], $data['start_date'], $data['end_date']))
                ->requiresConfirmation()
                ->modalButton('Generate')
                ->form([
                    Select::make('day')
                        ->label('Day')
                        ->options([
                            0 => 'Sunday',
                            1 => 'Monday',
                            2 => 'Tuesday',
                            3 => 'Wednesday',
                            4 => 'Thursday',
                            5 => 'Friday',
                            6 => 'Saturday',
                        ]),
                    DateTimePicker::make('start_date')
                        ->label('Lecture Start')
                        ->displayFormat('h:i A')
                        ->withoutDate()
                        ->withoutSeconds()
                        ->required(),
                    DateTimePicker::make('end_date')
                        ->label('Lecture End')
                        ->displayFormat('h:i A')
                        ->withoutDate()
                        ->withoutSeconds()
                        ->required(),
                ]),
            DeleteAction::make(),
            ForceDeleteAction::make()->label('Force Delete'),
            RestoreAction::make(),
        ];
    }
}
