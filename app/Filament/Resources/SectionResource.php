<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SectionResource\Pages\CreateSection;
use App\Filament\Resources\SectionResource\Pages\EditSection;
use App\Filament\Resources\SectionResource\Pages\ListSections;
use App\Filament\Resources\SectionResource\RelationManagers\EnrollmentsRelationManager;
use App\Filament\Resources\SectionResource\RelationManagers\LecturesRelationManager;
use App\Models\Section;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SectionResource extends Resource
{
    protected static ?string $model = Section::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Tuition';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Section Metadata')
                    ->schema([
                        Select::make('course_id')
                            ->label('Course')
                            ->searchable()
                            ->relationship('course', 'name')
                            ->required(),
                        Select::make('pricing_id')
                            ->label('Pricing Tier')
                            ->searchable()
                            ->relationship('pricing', 'name')
                            ->preload()
                            ->required(),
                        TextInput::make('code')
                            ->label('Section Code')
                            ->maxLength(3)
                            ->required(),
                    ]),
                Fieldset::make('Azure Metadata')
                    ->schema([
                        TextInput::make('azure_team_id')
                            ->label('Azure Team ID')
                            ->required(),
                        TextInput::make('channel_folder')
                            ->label('Team Channel Name'),
                        TextInput::make('recordings_folder')
                            ->label('Recordings Folder'),
                    ]),
                Fieldset::make('Lecture Delivery')
                    ->schema([
                        DateTimePicker::make('start_date')
                            ->label('Term Start')
                            ->displayFormat('l, d F Y')
                            ->withoutTime()
                            ->required(),
                        DateTimePicker::make('end_date')
                            ->label('Term End')
                            ->displayFormat('l, d F Y')
                            ->withoutTime()
                            ->required(),
                        Select::make('delivery_method')
                            ->label('Delivery Method')
                            ->options([
                                'Online' => 'Online',
                                'In Person' => 'In Person',
                                'Hybrid' => 'Hybrid',
                            ])
                            ->required(),
                        TextInput::make('seats')
                            ->label('Seats')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('course.name')->label('Course')->sortable(),
                TextColumn::make('course.tutor.name')->label('Tutor')->sortable(),
                TextColumn::make('code')->label('Section Code')->sortable(),
                TextColumn::make('delivery_method')->label('Delivery Method')->sortable(),
                TextColumn::make('seats')->label('Seats')->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make()->label('Delete Selected'),
                RestoreBulkAction::make()->label('Restore Selected'),
                ForceDeleteBulkAction::make()->label('Force Delete Selected'),
            ]);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Tutor' => $record->course->tutor->name,
        ];
    }

    public static function getRelations(): array
    {
        return [
            LecturesRelationManager::class,
            EnrollmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSections::route('/'),
            'create' => CreateSection::route('/create'),
            'edit' => EditSection::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->hasRole('icarus')) {
            return parent::getEloquentQuery()
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]);
        }

        if (auth()->user()->hasRole('tutor')) {
            return parent::getEloquentQuery()
                ->taughtBy(auth()->user())
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]);
        }

        return parent::getEloquentQuery()
            ->studentEnrolled(auth()->user())
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
