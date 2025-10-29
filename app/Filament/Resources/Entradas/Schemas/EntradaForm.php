<?php
namespace App\Filament\Resources\Entradas\Schemas; 

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select; 
use Filament\Forms\Components\Textarea; 
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EntradaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                
                // Produto (Chave estrangeira para a Tabela produtos)
                Select::make('produto_id')
                    ->label('Produto Comprado')
                    // Isso pressupõe um relacionamento 'produto' no seu Model Entrada:
                    // public function produto() { return $this->belongsTo(Produto::class); }
                    ->relationship('produto', 'nome')
                    ->searchable()
                    ->required()
                    ->columnSpan(2),
                
                // Data da Entrada
                DatePicker::make('data')
                    ->label('Data da Compra')
                    ->required()
                    ->default(now()),

                // Quantidade Adicionada
                TextInput::make('quantidade')
                    ->label('Quantidade Adicionada')
                    ->numeric()
                    ->step(0.01) // Permite valores decimais (ex: 1.5 kg)
                    ->required(),

                // Preço de Custo (O preço total pago pela quantidade comprada)
                TextInput::make('preco_custo')
                    ->label('Preço Total da Compra (R$)')
                    ->numeric()
                    ->step(0.01)
                    ->prefix('R$')
                    ->required(),

                // Observações (Multiplas linhas)
                Textarea::make('observacao')
                    ->label('Observações da Compra')
                    ->maxLength(255)
                    ->columnSpanFull(), // Ocupa a linha inteira para o campo de texto

            ]);
    }
}