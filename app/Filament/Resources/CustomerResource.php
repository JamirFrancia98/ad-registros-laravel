<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = "Cliente";

    protected static ?string $pluralLabel = "Clientes";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->label('Nombres')
                    ->maxLength(80),
                Forms\Components\TextInput::make('last_name')
                    ->label('Apellidos')
                    ->maxLength(80),
                Forms\Components\TextInput::make('phone')
                    ->label('Celular')
                    ->tel()
                    ->maxLength(30),
                Forms\Components\TextInput::make('email')
                    ->label('Correo')
                    ->email()
                    ->maxLength(120),
                Forms\Components\Select::make('document_type')
                    ->label('Tipo de documento')
                    ->options([
                        'DNI' => 'DNI',
                        'C.E' => 'C.E',
                        'Otro' => 'Otro',
                    ])
                    ->placeholder('Selecciona'),
                Forms\Components\TextInput::make('document_number')
                    ->label('Documento de Identidad')
                    ->maxLength(20),
                Forms\Components\Select::make('operator')
                    ->label('Operador')
                    ->options([
                        'Claro' => 'Claro',
                        'Movistar' => 'Movistar',
                        'Entel' => 'Entel',
                        'Bitel' => 'Bitel',
                        'Otro' => 'Otro',
                    ])
                    ->placeholder('Selecciona'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Nombres')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Apellidos')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Celular')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('document_type')
                    ->badge()
                    ->label('Tipo de documento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('document_number')
                    ->label('Documento de Identidad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('operator')
                    ->badge()
                    ->label('Operador')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado el')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
