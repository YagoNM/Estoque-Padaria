<?php

namespace App\Filament\Resources\Produtos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProdutosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                
                TextColumn::make('nome')
                    ->label('Produto')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                // Categoria (Exibe como um "badge" visual)
                TextColumn::make('categoria')
                    ->label('Categoria')
                    ->badge(),

                // Estoque Atual (Com Alerta de Estoque Baixo)
                TextColumn::make('quantidade')
                    ->label('Estoque Atual')
                    ->numeric(decimalPlaces: 2, thousandsSeparator: '.', decimalSeparator: ',') // Formato brasileiro
                    ->sortable()
                    
                    // Lógica ESSENCIAL: Cores de Alerta
                    ->color(fn (float $state, Model $record): string => match (true) {
                        // Se o estoque atual ($state) for menor ou igual ao mínimo...
                        $state <= $record->min_quantidade => 'danger', // COR VERMELHA (Comprar!)
                        
                        // Se estiver até 50% acima do mínimo (ex: Mínimo=10, se for 11 a 15)
                        $state <= ($record->min_quantidade * 1.5) => 'warning', // COR AMARELA (Ficar de olho)
                        
                        default => 'success', // COR VERDE (Estoque OK)
                    })
                    ->suffix(fn (Model $record): string => ' ' . $record->unidade), // Adiciona a unidade (kg, unid)

                // Quantidade Mínima (Referência)
                TextColumn::make('min_quantidade')
                    ->label('Mínimo')
                    ->numeric(decimalPlaces: 2, thousandsSeparator: '.', decimalSeparator: ','),

                // Data de Validade (Com Alerta de Proximidade)
                TextColumn::make('data_validade')
                    ->label('Validade')
                    ->date('d/m/Y') // Formato de data brasileiro
                    ->sortable()
                    
                    // Lógica de Alerta de Validade
                    ->color(fn ($state): string => match (true) {
                        // Se a validade for em até 30 dias (ou já passou)
                        $state && $state->lt(now()->addDays(30)) => 'danger', // VERMELHO
                        
                        // Se a validade for entre 30 e 90 dias
                        $state && $state->lt(now()->addDays(90)) => 'warning', // AMARELO
                        
                        default => 'gray',
                    }),
                
                TextColumn::make('preco_custo')
                    ->label('Custo Unit.')
                    ->money('BRL') // Formata como moeda Brasileira (R$)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Esconde por padrão, mas pode ser ativado

                // Fornecedor ID
                TextColumn::make('fornecedor_id')
                    ->label('ID Forn.')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Colunas de Auditoria
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                // Vamos adicionar um Filtro de Alerta (Opcional)
                // Isto permite ao usuário clicar e ver SÓ os itens em risco.
                \Filament\Tables\Filters\SelectFilter::make('status_estoque')
                    ->options([
                        'danger' => 'Estoque Baixo/Crítico',
                        'warning' => 'Abaixo do Ideal',
                        'success' => 'Estoque OK',
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        if (empty($data['value'])) {
                            return $query;
                        }

                        $status = $data['value'];

                        return match ($status) {
                            'danger' => $query->whereColumn('quantidade', '<=', 'min_quantidade'),
                            'warning' => $query->whereColumn('quantidade', '>', 'min_quantidade')
                                                ->whereColumn('quantidade', '<=', \DB::raw('min_quantidade * 1.5')),
                            'success' => $query->whereColumn('quantidade', '>', \DB::raw('min_quantidade * 1.5')),
                            default => $query,
                        };
                    })
                    ->label('Status do Estoque'),
                
                // Filtro para ver produtos com validade próxima
                \Filament\Tables\Filters\Filter::make('validade_proxima')
                    ->query(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->whereDate('data_validade', '<', now()->addDays(90)))
                    ->label('Validade Próxima (90 dias)'),
                
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}