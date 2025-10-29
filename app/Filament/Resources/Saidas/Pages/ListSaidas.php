<?php

namespace App\Filament\Resources\Saidas\Pages;

use App\Filament\Resources\Saidas\SaidaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSaidas extends ListRecords
{
    protected static string $resource = SaidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
