<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnrollmentResource\Pages\CreateEnrollment;
use App\Filament\Resources\EnrollmentResource\Pages\EditEnrollment;
use App\Filament\Resources\EnrollmentResource\Pages\ListEnrollments;
use App\Models\Enrollment;
use App\Models\Section;
use App\Models\User;
use Filament\Forms\Components\Select;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Academics';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // TODO: Course Select
                Select::make('section_id')
                    ->label('Section Code')
                    ->searchable()
                    ->relationship('section', 'code')
                    ->options(Section::all()->pluck('code', 'id'))
                    ->required(),
                Select::make('user_id')
                    ->label('Student')
                    ->searchable()
                    ->relationship('student', 'name')
                    ->options(User::all()->pluck('name', 'id'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('section.course.name')->label('Course')->sortable(),
                TextColumn::make('section.code')->label('Section Code')->sortable(),
                TextColumn::make('student.name')->label('Student')->sortable(),
                TextColumn::make('student.email')->label('Personal Email')->sortable(),
                TextColumn::make('student.profile.azure_email')->label('Azure Email')->sortable(),
                TextColumn::make('student.profile.phone')->label('Phone Number')->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
            ]);
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
            'index' => ListEnrollments::route('/'),
            'create' => CreateEnrollment::route('/create'),
            'edit' => EditEnrollment::route('/{record}/edit'),
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
