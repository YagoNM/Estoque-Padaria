<?php

namespace App\Filament\Resources\Produtos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProdutoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                
                TextInput::make('nome')
                    ->label('Nome do Produto')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                
                // Categoria (Usando Select)
                Select::make('categoria')
                    ->label('Categoria')
                    ->options([
                        'Ingredientes' => 'Ingredientes',
                        'Produtos Prontos' => 'Produtos Prontos',
                        'Bebidas' => 'Bebidas',
                        'Outros' => 'Outros',
                    ])
                    ->required(),
                
                // Unidade de Medida (Usando Select)
                Select::make('unidade')
                    ->label('Unidade de Medida')
                    ->options([
                        'unid' => 'Unidade (unid)',
                        'kg' => 'Quilograma (kg)',
                        'litro' => 'Litro (litro)',
                        'pacote' => 'Pacote (pct)',
                    ])
                    ->required(),

                // Quantidade Atual
                TextInput::make('quantidade')
                    ->label('Estoque Inicial')
                    ->numeric()
                    ->step(0.01)
                    ->default(0.0)
                    ->disabled() // Idealmente desabilitado, será atualizado nas Entradas/Saídas
                    ->helperText('O estoque deve ser atualizado na tela de Entradas/Saídas.'),

                // Quantidade Mínima (Para alerta)
                TextInput::make('min_quantidade')
                    ->label('Estoque Mínimo (Alerta)')
                    ->required()
                    ->numeric()
                    ->step(0.01)
                    ->default(5.0),
                
                // Data de Validade
                DatePicker::make('data_validade')
                    ->label('Data de Validade'),

                // Fornecedor ID
                TextInput::make('fornecedor_id')
                    ->label('ID Fornecedor')
                    ->numeric(),

                // Preço de Custo (Útil para o cálculo futuro de custo médio)
                TextInput::make('preco_custo')
                    ->label('Preço Custo Unitário')
                    ->numeric()
                    ->step(0.01)
                    ->prefix('R$'),

            ]);
    }
}