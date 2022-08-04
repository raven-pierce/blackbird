<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use App\Models\Enrollment;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListEnrollments extends ListRecords
{
    protected static string $resource = EnrollmentResource::class;

    public function isTableSearchable(): bool
    {
        return true;
    }

    protected function applySearchToTableQuery(Builder $query): Builder
    {
        if (filled($searchQuery = $this->getTableSearchQuery())) {
            $query->whereIn('id', Enrollment::search($searchQuery)->keys());
        }

        return $query;
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
