<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages\CreateReport;
use App\Filament\Resources\ReportResource\Pages\EditReport;
use App\Filament\Resources\ReportResource\Pages\ListReports;
use App\Filament\Resources\ReportResource\Pages\ViewReport;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Report;
use App\Models\Section;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationGroup = 'Academics';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('enrollment.section.course_id')
                    ->label('Course')
                    ->searchable()
                    ->options(Course::all()->pluck('name', 'id'))
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('enrollment.section_id', null))
                    ->dehydrated(false)
                    ->required(),
                Select::make('enrollment.section_id')
                    ->label('Section Code')
                    ->searchable()
                    ->options(function (\Closure $get) {
                        $course = Course::find($get('enrollment.section.course_id'));

                        if (! $course) {
                            return Section::all()->pluck('code', 'id');
                        }

                        return $course->sections->pluck('code', 'id');
                    })
                    ->afterStateUpdated(fn (callable $set) => $set('enrollment_id', null))
                    ->reactive()
                    ->required(),
                Select::make('enrollment_id')
                    ->label('Student')
                    ->searchable()
                    ->relationship('enrollment', 'id', function (Builder $query, \Closure $get) {
                        $section = Section::find($get('enrollment.section_id'));

                        if (! $section) {
                            return $query;
                        }

                        return $query->whereBelongsTo($section);
                    })
                    ->preload()
                    ->afterStateHydrated(function (\Closure $set, $state, $context) {
                        if ($context === 'edit') {
                            $set('enrollment.section.course_id', Enrollment::find($state)->section->course_id);
                            $set('enrollment.section_id', Enrollment::find($state)->section_id);
                        }
                    })
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => $record->student->name)
                    ->required(),
                DateTimePicker::make('start_date')->label('Start Date')->withoutSeconds()->required(),
                DateTimePicker::make('end_date')->label('End Date')->withoutSeconds()->required(),
                MarkdownEditor::make('remarks')->label('Remarks')->columnSpan(2)
                    ->disableToolbarButtons([
                        'attachFiles',
                        'codeBlock',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('enrollment.section.course.name')->label('Course')->sortable(),
                TextColumn::make('enrollment.section.code')->label('Section Code')->sortable(),
                TextColumn::make('enrollment.student.name')->label('Student')->sortable(),
                TextColumn::make('start_date')->label('Start Date')->dateTime('l, d F Y')->sortable(),
                TextColumn::make('end_date')->label('End Date')->dateTime('l, d F Y')->sortable(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReports::route('/'),
            'create' => CreateReport::route('/create'),
            'view' => ViewReport::route('/{record}'),
            'edit' => EditReport::route('/{record}/edit'),
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
