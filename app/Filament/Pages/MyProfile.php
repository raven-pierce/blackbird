<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use JeffGreco13\FilamentBreezy\Pages\MyProfile as BaseProfile;

class MyProfile extends BaseProfile
{
    protected function getUpdateProfileFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label(__('filament-breezy::default.fields.name')),
            TextInput::make($this->loginColumn)->unique(config('filament-breezy.user_model'), ignorable: $this->user)
                ->label(__('filament-breezy::default.fields.email'))
                ->disabled(fn () => ! auth()->user()->hasRole('icarus')),
        ];
    }
}
