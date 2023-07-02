<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Imports\Users;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use STS\FilamentImpersonate\Impersonate;
use Twilio\Rest\Client;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 999;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Full Name')
                    ->required(),
                TextInput::make('email')
                    ->label('Azure Email')
                    ->email()
                    ->autocomplete('email')
                    ->required(),
                TextInput::make('password')
                    ->label('New Password')
                    ->password()
                    ->autocomplete('new-password')
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
                Select::make('roles')
                    ->label('Roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload()
                    ->required()
                    ->visible(auth()->user()->hasRole('icarus')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Full Name')->sortable(),
                TextColumn::make('email')->label('Azure Email')->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                Impersonate::make('impersonate'),
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make()->label('Delete Selected'),
                RestoreBulkAction::make()->label('Restore Selected'),
                ForceDeleteBulkAction::make()->label('Force Delete Selected'),
            ])
            ->headerActions([
                Action::make('importContacts')
                    ->label('Import Contacts')
                    ->action(function (array $data) {
                        $contacts = Excel::toCollection(new Users(), $data['xlsx'])->flatten(1);

                        $twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));

                        foreach ($contacts as $contact) {
                            $user = User::firstWhere('phone', $twilio->lookups->v2->phoneNumbers($contact['phone'])->fetch()->phoneNumber);

                            if (! $user) {
                                Notification::make()
                                    ->title('User Not Found')
                                    ->body("A user with the phone number {$twilio->lookups->v2->phoneNumbers($contact['phone'])->fetch()->phoneNumber} could not be found.")
                                    ->danger()
                                    ->send();

                                continue;
                            }
                            $user->alternate_email = $contact['alternate_email'];
                            $user->guardian_email = $contact['guardian_email'];
                            $user->guardian_phone = $twilio->lookups->v2->phoneNumbers($contact['guardian_phone'])->fetch()->phoneNumber;
                            $user->save();
                        }

                        Storage::disk('local')->delete($data['xlsx']);

                        Notification::make()
                            ->title('Contacts Imported')
                            ->success()
                            ->send();
                    })
                    ->form([
                        FileUpload::make('xlsx')->label('Contacts Sheet')->disk('local')->required(),
                    ])
                    ->visible(fn () => auth()->user()->hasRole('icarus')),
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
