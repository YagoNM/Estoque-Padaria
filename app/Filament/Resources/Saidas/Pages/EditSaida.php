<?php

namespace App\Filament\Resources\Saidas\Pages;

use App\Filament\Resources\Saidas\SaidaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSaida extends EditRecord
{
    protected static string $resource = SaidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
