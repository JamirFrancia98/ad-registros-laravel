<?php

namespace App\Filament\Resources\StorageOptionResource\Pages;

use App\Filament\Resources\StorageOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStorageOptions extends ListRecords
{
    protected static string $resource = StorageOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
