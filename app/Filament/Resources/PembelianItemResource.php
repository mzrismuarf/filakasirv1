<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Pembelian;
use Filament\Tables\Table;
use Illuminate\Http\Request;
use App\Models\PembelianItem;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PembelianItemResource\Pages;
use App\Filament\Resources\PembelianItemResource\RelationManagers;
use Filament\Forms\Get;

class PembelianItemResource extends Resource
{
    protected static ?string $model = PembelianItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        // fix [Attempt to read property "tanggal" on null]
        $pembelian = new Pembelian();
        if (request()->has('pembelian_id')) {

            $pembelian = \App\Models\Pembelian::find(request('pembelian_id'));
        }

        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        DateTimePicker::make('tanggal')
                            ->label('Tanggal Pembelian')
                            ->required()
                            ->default($pembelian->tanggal)
                            ->disabled(),
                        TextInput::make('supplier_id')
                            ->label('Supplier')
                            ->required()
                            ->disabled()
                            ->default($pembelian->supplier?->nama),
                        TextInput::make('supplier_id')
                            ->label('Email Supplier')
                            ->required()
                            ->disabled()
                            ->default($pembelian->supplier?->email),
                    ])->columns(3),
                Grid::make()
                    ->schema([
                        Select::make('barang_id')
                            ->label('Barang')
                            ->required()
                            ->options(
                                \App\Models\Barang::all()->pluck('nama', 'id')
                            )
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $barang = \App\Models\Barang::find($state);
                                $set('harga', $barang->harga ?? null);
                                $jumlah = $get('jumlah');
                                $total = $jumlah * $barang->harga;
                                $set('total', $total);
                            }),
                        TextInput::make('harga')
                            ->label('Harga Barang')->required(),
                        TextInput::make('jumlah')
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $jumlah = $state;
                                $harga = $get('harga');
                                $total = $jumlah * $harga;
                                $set('total', $total);
                            })
                            ->label('Jumlah Barang')->required()->default(1),
                        TextInput::make('total')
                            ->disabled()
                            ->label('Total Harga'),

                    ])->columns(4),
                Hidden::make('pembelian_id')
                    ->default(request('pembelian_id')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListPembelianItems::route('/'),
            'create' => Pages\CreatePembelianItem::route('/create'),
            'edit' => Pages\EditPembelianItem::route('/{record}/edit'),
        ];
    }
}
