<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use Filament\Resources\Pages\ViewRecord;

class ViewReport extends ViewRecord
{
    protected static string $resource = ReportResource::class;

    protected static string $view = 'filament.resources.reports.pages.view-report';

    public function getViewData(): array
    {
        return [
            'lectures' => $this->record->enrollment->section->lecturesBetween($this->record->start_date, $this->record->end_date)->get(),
            'assessments' => $this->record->enrollment->section->assessmentsBetween($this->record->start_date, $this->record->end_date)->get(),
        ];
    }
}
