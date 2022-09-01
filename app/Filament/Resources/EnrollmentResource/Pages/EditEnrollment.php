<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
            Action::make()
                ->label('Send Invoice')
                ->action(fn (array $data) => $this->record->generateInvoice($data['quantity'], $data['override']))
                ->requiresConfirmation()
                ->modalSubheading('Generate and send a student their latest invoice?')
                ->modalButton('Send Invoice')
                ->form([
                    TextInput::make('quantity')
                        ->label('Quantity')
                        ->numeric(),
                    Toggle::make('override')
                        ->label('Override Payment Threshold?')
                        ->onIcon('heroicon-s-shield-exclamation')
                        ->offIcon('heroicon-s-shield-check')
                        ->required(),
                ]),
            DeleteAction::make(),
            ForceDeleteAction::make()->label('Force Delete'),
            RestoreAction::make()->label('Restore'),
        ];
    }
}
