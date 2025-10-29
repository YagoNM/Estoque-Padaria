<?php

namespace App\Filament\Resources\Saidas;

use App\Filament\Resources\Saidas\Pages\CreateSaida;
use App\Filament\Resources\Saidas\Pages\EditSaida;
use App\Filament\Resources\Saidas\Pages\ListSaidas;
use App\Filament\Resources\Saidas\Pages\ViewSaida;
use App\Filament\Resources\Saidas\Schemas\SaidaForm;
use App\Filament\Resources\Saidas\Schemas\SaidaInfolist;
use App\Filament\Resources\Saidas\Tables\SaidasTable;
use App\Models\Saida;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SaidaResource extends Resource
{
    protected static ?string $model = Saida::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return SaidaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SaidaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SaidasTable::configure($table);
    }

    public static function getModelListeners(): array
    {
        return [
            \App\Models\Saida::class => [
                
                // EVENTO: Quando uma nova Saída é criada (INSERT)
                'created' => [
                    function (\App\Models\Saida $record) {
                        
                        $produto = $record->produto; 

                        if ($produto) {
                            // Subtrai a 'quantidade' da saída do estoque do Produto (DECREMENTO)
                            $produto->decrement('quantidade', $record->quantidade);
                        }
                    },
                ],
                
                // EVENTO: Quando uma Saída é soft-deletada (DELETE LÓGICO)
                'deleted' => [
                    function (\App\Models\Saida $record) {
                        $produto = $record->produto;
                        
                        if ($produto) {
                            // Se a Saída é deletada, a quantidade deve ser RE-SOMADA (reversão) ao estoque
                            $produto->increment('quantidade', $record->quantidade);
                        }
                    },
                ],
            ],
        ];
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSaidas::route('/'),
            'create' => CreateSaida::route('/create'),
            'view' => ViewSaida::route('/{record}'),
            'edit' => EditSaida::route('/{record}/edit'),
        ];
    }
}