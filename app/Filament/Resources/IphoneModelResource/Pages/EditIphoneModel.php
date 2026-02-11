<?php

namespace App\Filament\Resources\IphoneModelResource\Pages;

use App\Filament\Resources\IphoneModelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIphoneModel extends EditRecord
{
    protected static string $resource = IphoneModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
