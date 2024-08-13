<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Pembelian;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\PembelianResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PembelianResource\RelationManagers;
use Filament\Tables\Columns\TextColumn;

class PembelianResource extends Resource
{
    protected static ?string $model = Pembelian::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $label = 'Data Pembelian';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DateTimePicker::make('tanggal')
                    ->label('Tanggal Pembelian')
                    ->required()
                    ->default(now())->columnSpanFull(),
                Select::make('supplier_id')
                    ->options(
                        \App\Models\Supplier::pluck('nama_perusahaan', 'id')
                    )->required()
                    ->label('Pilih Supplier')
                    ->searchable()
                    ->createOptionForm(
                        \App\Filament\Resources\SupplierResource::getForm()
                    )->createOptionUsing(function (array $data): int {
                        return \App\Models\Supplier::create($data)->id;
                    })
                    ->reactive()
                    ->afterStateUpdated(function ($state, Set $set) {
                        $supplier = \App\Models\Supplier::find($state);
                        $set('email', $supplier->email ?? null);
                    }),

                Forms\Components\TextInput::make('email')->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('supplier.nama_perusahaan')->label('Nama Supplier'),
                TextColumn::make('supplier.nama')->label('Nama Penghubung (Sales)'),
                TextColumn::make('tanggal')->dateTime('d F Y')->label('Tanggal Pembelian'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPembelians::route('/'),
            'create' => Pages\CreatePembelian::route('/create'),
            'edit' => Pages\EditPembelian::route('/{record}/edit'),
        ];
    }
}
