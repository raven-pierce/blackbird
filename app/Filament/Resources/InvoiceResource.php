<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages\CreateInvoice;
use App\Filament\Resources\InvoiceResource\Pages\EditInvoice;
use App\Filament\Resources\InvoiceResource\Pages\ListInvoices;
use App\Filament\Resources\InvoiceResource\Pages\ViewInvoice;
use App\Models\Invoice;
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
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-cash';

    protected static ?string $navigationGroup = 'Tuition';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('external_id')
                    ->label('Invoice Reference')
                    ->numeric()
                    ->required(),
                Select::make('user_id')
                    ->label('Customer Name')
                    ->searchable()
                    ->relationship('user', 'name')
                    ->preload()
                    ->required(),
                TextInput::make('invoice_url')
                    ->label('Invoice URL')
                    ->url()
                    ->required(),
                TextInput::make('amount')
                    ->label('Total')
                    ->numeric()
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Paid' => 'Paid',
                        'Unpaid' => 'Unpaid',
                        'Void' => 'Void',
                    ])->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('external_id')->label('Reference')->sortable(),
                TextColumn::make('created_at')->label('Date')->dateTime('l, d F Y')->sortable(),
                TextColumn::make('user.name')->label('Recipient')->sortable(),
                TextColumn::make('amount')->label('Total')->sortable(),
                BadgeColumn::make('status')->label('Status')->sortable()->enum([
                    'Paid' => 'Paid',
                    'Unpaid' => 'Unpaid',
                    'Void' => 'Void',
                ])->colors([
                    'primary',
                    'success' => 'Paid',
                    'warning' => 'Unpaid',
                    'danger' => 'Void',
                ])->icons([
                    'heroicon-s-badge-check' => 'Paid',
                    'heroicon-s-x-circle' => 'Unpaid',
                    'heroicon-s-ban' => 'Void',
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                Action::make('payInvoice')
                    ->label('Pay')
                    ->icon('heroicon-s-cash')
                    ->url(fn (Invoice $record) => $record->invoice_url)
                    ->visible(fn (Invoice $record) => $record->status === 'Unpaid'),
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
            'index' => ListInvoices::route('/'),
            'create' => CreateInvoice::route('/create'),
            'view' => ViewInvoice::route('/{record}'),
            'edit' => EditInvoice::route('/{record}/edit'),
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

        return parent::getEloquentQuery()
            ->whereBelongsTo(auth()->user())
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
