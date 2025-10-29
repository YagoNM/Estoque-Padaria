<?php

namespace App\Filament\Resources\Saidas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SaidaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('produto_id')
                    ->numeric(),
                TextEntry::make('quantidade')
                    ->numeric(),
                TextEntry::make('data')
                    ->date(),
                TextEntry::make('motivo')
                    ->placeholder('-'),
                TextEntry::make('observacoes')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
