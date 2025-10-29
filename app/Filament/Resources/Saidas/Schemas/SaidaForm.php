<?php

namespace App\Filament\Resources\Saidas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SaidaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                
                // 1. Produto (Chave estrangeira)
                Select::make('produto_id')
                    ->label('Produto Retirado')
                    ->relationship('produto', 'nome')
                    ->searchable()
                    ->required()
                    ->columnSpan(2),
                
                // 2. Data da Saída
                DatePicker::make('data')
                    ->label('Data da Retirada')
                    ->required()
                    ->default(now()),

                // 3. Motivo da Saída (Dropdown com opções comuns da padaria)
                Select::make('motivo')
                    ->label('Motivo da Saída')
                    ->options([
                        'Produção Interna' => 'Produção Interna',
                        'Venda' => 'Venda',
                        'Perda/Descarte' => 'Perda/Descarte',
                        'Teste/Amostra' => 'Teste/Amostra',
                    ])
                    ->required(),
                
                // 4. Quantidade Retirada
                TextInput::make('quantidade')
                    ->label('Quantidade Retirada')
                    ->numeric()
                    ->step(0.01)
                    ->required(),

                // 5. Observações
                Textarea::make('observacao')
                    ->label('Observações Adicionais')
                    ->maxLength(255)
                    ->columnSpanFull(),

            ]);
    }
}
