<?php

namespace App\Filament\Resources\Saidas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;

class SaidasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                
                TextColumn::make('data')
                    ->label('Data')
                    ->date('d/m/Y') 
                    ->sortable()
                    ->searchable(),

                TextColumn::make('produto.nome')
                    ->label('Produto')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('motivo')
                    ->label('Motivo')
                    ->badge()
                    // Cores para identificar o tipo de Saída
                    ->color(fn (string $state): string => match ($state) {
                        'Venda' => 'success', // Verde para vendas
                        'Produção Interna' => 'warning', // Amarelo para uso em produção
                        'Perda/Descarte' => 'danger', // Vermelho para perdas
                        default => 'gray',
                    }),

                TextColumn::make('quantidade')
                    ->label('Quantidade')
                    ->numeric(decimalPlaces: 2, thousandsSeparator: '.', decimalSeparator: ',')
                    ->sortable()
                    // Se o seu Model Saida tiver o relacionamento com a unidade do Produto,
                    // você pode fazer um append:
                    // ->suffix(fn (Model $record): string => ' ' . $record->produto->unidade),
                    // Por enquanto, fica sem suffix para simplificar.
                    ,

                // Observações (Escondido por padrão, para evitar poluir a tabela)
                TextColumn::make('observacao')
                    ->label('Observações')
                    ->toggleable(isToggledHiddenByDefault: true), 

                // Data de Criação do Registro
                TextColumn::make('created_at')
                    ->label('Registrado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
            ])
            ->filters([
                // Filtro para Motivo (Permite ver só Perdas ou só Vendas)
                SelectFilter::make('motivo')
                    ->options([
                        'Produção Interna' => 'Produção Interna',
                        'Venda' => 'Venda',
                        'Perda/Descarte' => 'Perda/Descarte',
                    ])
                    ->label('Filtrar por Motivo'),
                
                // Filtro para Produto (Permite ver o histórico de um item específico)
                SelectFilter::make('produto')
                    ->relationship('produto', 'nome')
                    ->label('Filtrar por Produto')
                    ->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
                // Como usamos soft-deletes no Model, o delete aqui reverte o estoque automaticamente!
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}