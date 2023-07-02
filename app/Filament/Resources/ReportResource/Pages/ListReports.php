<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use App\Jobs\RunReport;
use App\Models\Enrollment;
use App\Models\Report;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    public function isTableSearchable(): bool
    {
        return true;
    }

    protected function applySearchToTableQuery(Builder $query): Builder
    {
        if (filled($searchQuery = $this->getTableSearchQuery())) {
            $query->whereIn('id', Report::search($searchQuery)->keys());
        }

        return $query;
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-document-duplicate';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No Reports Yet';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Here you will be able to find and run reports for any enrollments.';
    }

    protected function getActions(): array
    {
        return [
            Action::make('runReports')
                ->label('Run Reports')
                ->action(fn (array $data) => Enrollment::all()->each(fn (Enrollment $enrollment) => RunReport::dispatch($enrollment, Carbon::parse($data['start_date']), Carbon::parse($data['end_date']))))
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
                ])
                ->visible(fn () => auth()->user()->hasRole('icarus')),
            CreateAction::make()->label('New Report'),
        ];
    }
}
