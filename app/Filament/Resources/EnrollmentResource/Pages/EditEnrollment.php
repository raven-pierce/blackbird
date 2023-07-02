<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\DeleteAction;
use Filament\Pages\Actions\ForceDeleteAction;
use Filament\Pages\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditEnrollment extends EditRecord
{
    protected static string $resource = EnrollmentResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('sendInvoice')
                ->label('Send Invoice')
                ->action(fn (array $data) => $this->record->generateInvoice(Carbon::parse($data['start_date']), Carbon::parse($data['end_date']), $data['quantity']))
                ->requiresConfirmation()
                ->modalSubheading('Generate and send a student their latest monthly invoice?')
                ->modalButton('Send Invoice')
                ->form([
                    DateTimePicker::make('start_date')
                        ->label('From')
                        ->displayFormat('l, d F Y h:i A')
                        ->withoutSeconds(),
                    DateTimePicker::make('end_date')
                        ->label('To')
                        ->displayFormat('l, d F Y h:i A')
                        ->withoutSeconds(),
                    TextInput::make('quantity')
                        ->label('Quantity')
                        ->numeric(),
                ]),
            DeleteAction::make(),
            ForceDeleteAction::make()->label('Force Delete'),
            RestoreAction::make(),
        ];
    }
}
