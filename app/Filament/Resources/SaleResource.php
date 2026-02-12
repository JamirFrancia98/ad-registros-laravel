<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Sale;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;


class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = "Venta";

    protected static ?string $pluralLabel = "Ventas";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('purchase_id')
                    ->label('Equipo')
                    ->required()
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        return Purchase::query()
                            ->with(['iphoneModel', 'storageOption'])
                            ->whereDoesntHave('sale')
                            ->where(function ($query) use ($search) {
                                $query->where('imei1', 'like', "%{$search}%")
                                    ->orWhere('id', 'like', "%{$search}%")
                                    ->orWhereHas('iphoneModel', function ($q) use ($search) {
                                        $q->where('name', 'like', "%{$search}%");
                                    });
                            })
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($purchase) {
                                return [
                                    $purchase->id => "#{$purchase->id} — "
                                        . "{$purchase->iphoneModel->name} "
                                        . "{$purchase->storageOption->label} — "
                                        . "IMEI: {$purchase->imei1} — "
                                        . "Costo: S/ " . number_format($purchase->purchase_price, 2)
                                ];
                            })
                            ->toArray();
                    })
                    ->getOptionLabelUsing(function ($value): ?string {
                        $purchase = Purchase::with(['iphoneModel', 'storageOption'])->find($value);

                        if (! $purchase) return null;

                        return "#{$purchase->id} — "
                            . "{$purchase->iphoneModel->name} "
                            . "{$purchase->storageOption->label} — "
                            . "IMEI: {$purchase->imei1} — "
                            . "Costo: S/ " . number_format($purchase->purchase_price, 2);
                    })
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {

                        if (!$state) {
                            $set('sold_price', null);
                            return;
                        }

                        $purchase = Purchase::find($state);

                        if ($purchase) {
                            $set('sold_price', $purchase->sale_price);
                        }
                    })
                    ->columnSpanFull(),
                Forms\Components\Select::make('customer_id')
                    ->label('Cliente')
                    ->required()
                    ->relationship(
                        name: 'customer',
                        titleAttribute: 'first_name',
                        modifyQueryUsing: function (Builder $query) {
                            $query->select([
                                'id',
                                'first_name',
                                'last_name',
                                'document_number',
                            ]);
                        }
                    )
                    ->getOptionLabelFromRecordUsing(function (Customer $record) {
                        return "{$record->first_name} {$record->last_name} - ({$record->document_number})";
                    })
                    ->searchable(['first_name', 'last_name', 'document_number'])
                    ->preload()
                    ->createOptionForm([

                        TextInput::make('first_name')
                            ->label('Nombres')
                            ->required(),

                        TextInput::make('last_name')
                            ->label('Apellidos'),

                        TextInput::make('phone')
                            ->label('Celular'),

                        TextInput::make('document_number')
                            ->label('Documento')
                            ->unique(Customer::class, 'document_number'),
                    ])
                    ->createOptionUsing(function (array $data) {
                        return Customer::firstOrCreate(
                            ['document_number' => $data['document_number']],
                            $data
                        );
                    })
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('sold_at')
                    ->label('Fecha de venta')
                    ->default(Carbon::now())
                    ->required(),
                Forms\Components\TextInput::make('sold_price')
                    ->label('Precio de venta')
                    ->required()
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $totalItems = (float) ($get('total_items') ?? 0);
                        $set('grand_total', (float) $state + $totalItems);
                    }),
                Forms\Components\Select::make('payment_method')
                    ->label('Método de Pago')
                    ->options([
                        'efectivo' => 'Efectivo',
                        'yape' => 'Yape',
                        'plin' => 'Plin',
                        'transferencia' => 'Transferencia',
                        'tarjeta' => 'Tarjeta',
                        'otros' => 'Otros',
                    ])
                    ->placeholder('Selecciona método de pago')
                    ->searchable()
                    ->native(false),
                Forms\Components\Select::make('channel')
                    ->label('Canal de Venta')
                    ->options([
                        'tienda' => 'Tienda',
                        'online' => 'Online',
                        'marketplace' => 'Marketplace',
                        'whatsapp' => 'WhatsApp',
                        'instagram' => 'Instagram',
                        'facebook' => 'Facebook',
                        'otros' => 'Otros',
                    ])
                    ->placeholder('Selecciona canal')
                    ->searchable()
                    ->native(false),
                Forms\Components\Textarea::make('notes')
                    ->label('Notas')
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {

                        $totalItems = 0;

                        foreach ($state ?? [] as $item) {
                            $qty = (float) ($item['qty'] ?? 0);
                            $price = (float) ($item['price'] ?? 0);
                            $totalItems += $qty * $price;
                        }

                        $set('total_items', $totalItems);

                        $soldPrice = (float) ($get('sold_price') ?? 0);
                        $set('grand_total', $soldPrice + $totalItems);
                    })
                    ->label('Agregar accesorios')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Descripción')
                            ->required(),

                        Forms\Components\TextInput::make('qty')
                            ->label('Cantidad')
                            ->numeric()
                            ->default(1)
                            ->required(),

                        Forms\Components\TextInput::make('price')
                            ->label('Precio')
                            ->numeric()
                            ->required(),

                        Forms\Components\Select::make('type')
                            ->label('Tipo')
                            ->options([
                                'accessory' => 'Accesorio',
                                'service' => 'Servicio',
                                'other' => 'Otro',
                            ])
                            ->default('accessory'),
                    ])
                    ->columns(4)
                    ->defaultItems(0)
                    ->addActionLabel('Agregar accesorio')
                    ->collapsible()
                    ->columnSpanFull(),
                Forms\Components\Hidden::make('total_items')
                    ->default(0.00),
                Forms\Components\Hidden::make('grand_total')
                    ->default(0.00),
                Forms\Components\Section::make('Resumen de Venta')
                    ->description('Se actualiza automáticamente al seleccionar un equipo.')
                    ->schema([
                        Forms\Components\Placeholder::make('purchase_summary')
                            ->label('Resumen')
                            ->content(function ($get) {

                                $purchaseId = $get('purchase_id');

                                if (! $purchaseId) {
                                    return new HtmlString('<span class="text-gray-500">Selecciona un equipo para ver el resumen.</span>');
                                }

                                $purchase = Purchase::with(['iphoneModel', 'storageOption'])
                                    ->find($purchaseId);

                                if (! $purchase) {
                                    return 'Equipo no encontrado.';
                                }

                                $imeiLast = substr($purchase->imei1, -4);

                                $soldPrice   = (float) ($get('sold_price') ?? 0);
                                $totalItems  = (float) ($get('total_items') ?? 0);
                                $grandTotal  = (float) ($get('grand_total') ?? 0);

                                return new HtmlString("
                                    <div class='space-y-2'>
                
                                        <div class='text-lg font-semibold text-primary-600'>
                                            {$purchase->iphoneModel->name} {$purchase->storageOption->label}
                                        </div>
                
                                        <div class='text-sm text-gray-600'>
                                            IMEI (últ. 4): <strong>{$imeiLast}</strong>
                                        </div>
                
                                        <hr class='my-2'>
                
                                        <div class='flex justify-between'>
                                            <span>Precio equipo</span>
                                            <span class='font-medium'>S/ " . number_format($soldPrice, 2) . "</span>
                                        </div>
                
                                        <div class='flex justify-between'>
                                            <span>Accesorios</span>
                                            <span class='font-medium'>S/ " . number_format($totalItems, 2) . "</span>
                                        </div>
                
                                        <hr class='my-2'>
                
                                        <div class='flex justify-between text-lg font-bold text-success-600'>
                                            <span>Total Venta</span>
                                            <span>S/ " . number_format($grandTotal, 2) . "</span>
                                        </div>
                
                                    </div>
                                ");
                            }),
                    ])
                    ->columnSpanFull()
                    ->live()
                    ->visible(fn($get) => filled($get('purchase_id'))),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 📱 Modelo del iPhone
                TextColumn::make('purchase.iphoneModel.name')
                    ->label('Modelo')
                    ->searchable()
                    ->sortable(),

                // 💾 Almacenamiento
                TextColumn::make('purchase.storageOption.label')
                    ->label('Almacenamiento')
                    ->sortable(),

                // 👤 Cliente
                TextColumn::make('customer.full_name')
                    ->label('Cliente')
                    ->searchable([
                        'customers.first_name',
                        'customers.last_name',
                    ])
                    ->sortable(),

                TextColumn::make('customer.phone')
                    ->label('Teléfono')
                    ->searchable(),

                // 📅 Fecha
                TextColumn::make('sold_at')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                // 💰 Precio equipo
                TextColumn::make('sold_price')
                    ->label('Precio Equipo')
                    ->money('PEN')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // 🎧 Accesorios
                TextColumn::make('total_items')
                    ->label('Accesorios')
                    ->money('PEN')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // 💵 Total venta (DESTACADO)
                TextColumn::make('grand_total')
                    ->label('Total Venta')
                    ->money('PEN')
                    ->sortable(),
                // ->weight('bold')
                // ->size(TextColumn\TextColumnSize::Large),

                // 💳 Método de pago
                TextColumn::make('payment_method')
                    ->label('Método de Pago')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'efectivo' => 'success',
                        'yape', 'plin' => 'info',
                        'transferencia' => 'warning',
                        'tarjeta' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => ucfirst($state)),

                // 🏪 Canal
                TextColumn::make('channel')
                    ->label('Canal')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'tienda' => 'primary',
                        'online' => 'success',
                        'marketplace' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => ucfirst($state)),

                // 📝 Notas (oculto por defecto)
                TextColumn::make('notes')
                    ->label('Notas')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),

                // 🕒 Timestamps ocultos
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
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
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
