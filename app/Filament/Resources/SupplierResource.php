<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'Data Supplier';

    public static function getForm()
    {
        return [
            Forms\Components\TextInput::make('nama_perusahaan')
                ->label('Nama Perusahaan')
                ->minLength(3)
                ->maxLength(255),
            Forms\Components\TextInput::make('nama')
                ->label('Nama Kontak')
                ->minLength(3)
                ->maxLength(255)
                ->required(),
            Forms\Components\TextInput::make('no_hp')
                ->label('Nomor Telepon')
                ->tel()
                ->maxLength(14),
            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->maxLength(255),
            Forms\Components\TextInput::make('alamat')
                ->label('Alamat')
                ->minLength(3)
                ->maxLength(255),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                self::getForm()
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_perusahaan')->searchable(),
                Tables\Columns\TextColumn::make('nama')->label('Nama Kontak')->searchable(),
                Tables\Columns\TextColumn::make('no_hp')->label('Nomor Hp')->searchable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('alamat'),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
