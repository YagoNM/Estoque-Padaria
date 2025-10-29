<?php

namespace App\Filament\Resources\Entradas;

use App\Filament\Resources\Entradas\Pages\CreateEntrada;
use App\Filament\Resources\Entradas\Pages\EditEntrada;
use App\Filament\Resources\Entradas\Pages\ListEntradas;
use App\Filament\Resources\Entradas\Pages\ViewEntrada;
use App\Filament\Resources\Entradas\Schemas\EntradaForm;
use App\Filament\Resources\Entradas\Schemas\EntradaInfolist;
use App\Filament\Resources\Entradas\Tables\EntradasTable;
use App\Models\Entrada;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EntradaResource extends Resource
{
    protected static ?string $model = Entrada::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return EntradaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EntradaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EntradasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
    ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEntradas::route('/'),
            'create' => CreateEntrada::route('/create'),
            'view' => ViewEntrada::route('/{record}'),
            'edit' => EditEntrada::route('/{record}/edit'),
        ];
    }

    // 🚀 LÓGICA ESSENCIAL DE ESTOQUE: ADICIONADA AQUI!
    public static function getModelListeners(): array
    {
        return [
            \App\Models\Entrada::class => [
                
                // 1. Evento 'created': Soma a quantidade no estoque do Produto
                'created' => [
                    function (\App\Models\Entrada $record) {
                        $produto = $record->produto; 

                        if ($produto) {
                            // Soma a quantidade da Entrada na coluna 'quantidade' do Produto
                            $produto->increment('quantidade', $record->quantidade);
                        }
                    },
                ],
                
                // 2. Evento 'deleted': Subtrai a quantidade do estoque (graças ao SoftDeletes)
                'deleted' => [
                    function (\App\Models\Entrada $record) {
                        $produto = $record->produto;
                        
                        if ($produto) {
                            // Subtrai a quantidade quando a Entrada é excluída suavemente
                            $produto->decrement('quantidade', $record->quantidade);
                        }
                    },
                ],
            ],
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
    }
}