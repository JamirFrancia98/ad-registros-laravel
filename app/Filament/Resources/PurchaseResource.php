<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseResource\Pages;
use App\Filament\Resources\PurchaseResource\RelationManagers;
use App\Models\Color;
use App\Models\Purchase;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rules\Date;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = "Compra";

    protected static ?string $pluralLabel = "Compras";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('purchase_date')
                    ->label('Fecha de compra')
                    ->default(Carbon::now())
                    ->required(),
                Forms\Components\Select::make('supplier_id')
                    ->label('Proveedor')
                    ->relationship('supplier', 'name')
                    ->required(),
                Forms\Components\Select::make('iphone_model_id')
                    ->label('Modelo de iPhone')
                    ->relationship('iphoneModel', 'name')
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(fn(callable $set) => $set('color_id', null)),
                Forms\Components\Select::make('storage_option_id')
                    ->label('Opción de almacenamiento')
                    ->relationship('storageOption', 'label')
                    ->required(),
                Forms\Components\Select::make('color_id')
                    ->label('Color')
                    ->options(function (callable $get) {
                        $iphoneModelId = $get('iphone_model_id');

                        if (! $iphoneModelId) {
                            return [];
                        }

                        return Color::where('iphone_model_id', $iphoneModelId)
                            ->pluck('name', 'id');
                    })
                    ->disabled(fn(callable $get) => ! $get('iphone_model_id')) // 👈 se desactiva si no hay modelo
                    ->required(),
                Forms\Components\TextInput::make('imei1')
                    ->label('IMEI 1')
                    ->required()
                    ->maxLength(20),
                Forms\Components\TextInput::make('imei2')
                    ->label('IMEI 2 (Opcional)')
                    ->maxLength(20),
                Forms\Components\TextInput::make('serial')
                    ->label('Serie (Opcional)')
                    ->maxLength(50),
                Forms\Components\TextInput::make('purchase_price')
                    ->label('Precio de Compra')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('sale_price')
                    ->label('Precio de Venta')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('markup')
                    ->label('Margen')
                    ->options([
                        150 => '+150',
                        200 => '+200',
                        250 => '+250',
                        300 => '+300',
                    ])
                    ->placeholder('Selecciona')
                    ->required(),
                Forms\Components\FileUpload::make('imei_photo_path')
                    ->label('Foto del IMEI')
                    ->image()
                    ->disk('public')
                    ->directory('purchases')
                    ->visibility('public'),
                Forms\Components\FileUpload::make('phone_photo_path')
                    ->label('Foto del Teléfono')
                    ->image()
                    ->disk('public')
                    ->directory('purchases')
                    ->visibility('public')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('purchase_date')
                    ->label('Fecha de compra')
                    ->date()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('supplier.name')
                //     ->sortable(),
                Tables\Columns\TextColumn::make('iphoneModel.name')
                    ->label('iPhone')
                    ->sortable(),
                Tables\Columns\TextColumn::make('storageOption.label')
                    ->label('Almacenamiento')
                    ->sortable(),
                Tables\Columns\TextColumn::make('imei1')
                    ->label('IMEI')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('serial')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('imei_photo_path')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('phone_photo_path')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('purchase_price')
                    ->label('Precio de compra')
                    ->numeric()
                    ->money('PEN')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sale_price')
                    ->label('Precio de venta')
                    ->numeric()
                    ->money('PEN')
                    ->sortable(),
                // Tables\Columns\TextColumn::make('markup')
                //     ->numeric()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
            'index' => Pages\ListPurchases::route('/'),
            'create' => Pages\CreatePurchase::route('/create'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
        ];
    }
}
