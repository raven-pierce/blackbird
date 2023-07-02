<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssessmentResource\Pages\CreateAssessment;
use App\Filament\Resources\AssessmentResource\Pages\EditAssessment;
use App\Filament\Resources\AssessmentResource\Pages\ListAssessments;
use App\Models\Assessment;
use App\Models\Course;
use App\Models\Section;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Academics';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('section.course_id')
                    ->label('Course')
                    ->searchable()
                    ->options(Course::all()->pluck('name', 'id'))
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('section_id', null))
                    ->dehydrated(false)
                    ->required(),
                Select::make('section_id')
                    ->label('Section Code')
                    ->searchable()
                    ->relationship('section', 'code', function ($query, \Closure $get) {
                        $course = Course::find($get('section.course_id'));

                        if (! $course) {
                            return $query;
                        }

                        return $query->whereBelongsTo($course);
                    })
                    ->preload()
                    ->afterStateHydrated(function (\Closure $set, $state, $context) {
                        if ($context === 'edit') {
                            $set('section.course_id', Section::find($state)->course_id);
                        }
                    })
                    ->reactive()
                    ->required(),
                Fieldset::make('Metadata')->schema([
                    Select::make('type')
                        ->label('Type')
                        ->options([
                            'Quiz' => 'Quiz',
                            'Socrative' => 'Socrative',
                        ])
                        ->required(),
                    TextInput::make('topic')->label('Topic')->required(),
                    TextInput::make('max_score')->label('Max Score')->numeric()->required(),
                    TextInput::make('url')->label('URL')->url(),
                ]),
                Fieldset::make('Assessment Window')->schema([
                    DateTimePicker::make('release_date')->label('Release Date')->required(),
                    DateTimePicker::make('due_date')->label('Due Date')->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('section.course.name')->label('Course')->sortable(),
                TextColumn::make('type')->label('Type')->sortable(),
                TextColumn::make('topic')->label('Topic')->sortable(),
                TextColumn::make('release_date')->label('Release Date')->dateTime('h:i A, D d M Y')->sortable(),
                TextColumn::make('due_date')->label('Due Date')->dateTime('h:i A, D d M Y')->sortable(),
            ])
            ->defaultSort('release_date', 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                Action::make('openAssessment')
                    ->label('Open')
                    ->icon('heroicon-s-external-link')
                    ->visible(fn (Assessment $record): bool => (now()->greaterThanOrEqualTo($record->release_date) || auth()->user()->hasAnyRole(['icarus', 'tutor'])) && $record->type === 'Quiz')
                    ->hidden(fn (Assessment $record): bool => (now()->greaterThanOrEqualTo($record->due_date) && ! auth()->user()->hasAnyRole(['icarus', 'tutor'])) && $record->type === 'Quiz')
                    ->url(fn (Assessment $record) => $record->url)
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->bulkActions([
                ExportBulkAction::make()->exports([
                    ExcelExport::make()->fromTable(),
                ]),
                DeleteBulkAction::make()->label('Delete Selected'),
                RestoreBulkAction::make()->label('Restore Selected'),
                ForceDeleteBulkAction::make()->label('Force Delete Selected'),
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
            'index' => ListAssessments::route('/'),
            'create' => CreateAssessment::route('/create'),
            'edit' => EditAssessment::route('/{record}/edit'),
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
