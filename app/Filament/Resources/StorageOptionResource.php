<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StorageOptionResource\Pages;
use App\Filament\Resources\StorageOptionResource\RelationManagers;
use App\Models\StorageOption;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StorageOptionResource extends Resource
{
    protected static ?string $model = StorageOption::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = "Opción de almacenamiento";

    protected static ?string $navigationGroup = 'Compras Iphone';
    protected static ?string $navigationLabel = 'Atributos — Gb';
    protected static ?int $navigationSort = 12;

    protected static ?string $pluralLabel = "Opciones de almacenamiento";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->required()
                    ->maxLength(20),
                Forms\Components\TextInput::make('gb')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gb')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
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
            'index' => Pages\ListStorageOptions::route('/'),
            'create' => Pages\CreateStorageOption::route('/create'),
            'edit' => Pages\EditStorageOption::route('/{record}/edit'),
        ];
    }
}
