<?php

namespace App\Filament\Resources\Productos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\Storage;

class ProductosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('imagen')
        ->label('Img')
    ->getStateUsing(fn ($record) => $record->imagen
        ? asset('storage/' . $record->imagen)
        : null
    )
    ->height(40)
    ->square(),

    TextColumn::make('categoria.nombre')->label('Categoría')->sortable()->searchable(),
    TextColumn::make('nombre')->sortable()->searchable(),
    TextColumn::make('precio'),
    TextColumn::make('stock')->sortable(),
    IconColumn::make('activo')->boolean(),

            TextColumn::make('categoria.nombre')->label('Categoría')->sortable()->searchable(),
            TextColumn::make('nombre')->sortable()->searchable(),
            TextColumn::make('precio')->money('USD'),
            TextColumn::make('stock')->sortable(),
            IconColumn::make('activo')->boolean(),

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
