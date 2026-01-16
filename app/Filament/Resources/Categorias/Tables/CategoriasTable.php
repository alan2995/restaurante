<?php

namespace App\Filament\Resources\Categorias\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
class CategoriasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
        ->label('Nombre')
        ->searchable()
        ->sortable(),

    IconColumn::make('activo')
        ->label('Activo')
        ->boolean(),

    TextColumn::make('created_at')
        ->label('Creado')
        ->dateTime('d/m/Y H:i')
        ->sortable(),
        
       TextColumn::make('productos_count')
    ->counts('productos')
    ->label('Productos')
    ->sortable(),
            ])

            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
