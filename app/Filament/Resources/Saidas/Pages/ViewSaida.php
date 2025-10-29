<?php

namespace App\Filament\Resources\Saidas\Pages;

use App\Filament\Resources\Saidas\SaidaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSaida extends ViewRecord
{
    protected static string $resource = SaidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
