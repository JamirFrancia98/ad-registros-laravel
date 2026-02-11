<?php

namespace App\Filament\Resources\IphoneModelResource\Pages;

use App\Filament\Resources\IphoneModelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIphoneModels extends ListRecords
{
    protected static string $resource = IphoneModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
