<?php

namespace App\Filament\Resources\Ventas\VentaResource\RelationManagers;

use App\Models\Producto;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;



class DetallesRelationManager extends RelationManager
{
    protected static string $relationship = 'detalles';

   public function form(Schema $schema): Schema
{
    return $schema->components([
        Select::make('producto_id')
    ->label('Producto')
    ->options(fn () => Producto::query()
        ->where('activo', true)   // quita esta linea si no usas activo
        ->orderBy('nombre')
        ->pluck('nombre', 'id')
        ->toArray()
    )
    ->searchable()
    ->preload()
    ->required()
    ->reactive(),
        TextInput::make('cantidad')
            ->numeric()
            ->default(1)
            ->required()
            ->reactive()
            ->afterStateUpdated(function (Get $get, Set $set) {
                $set('importe', (float) $get('cantidad') * (float) $get('precio_unitario'));
            }),

        TextInput::make('precio_unitario')
            ->numeric()
            ->required()
            ->reactive()
            ->afterStateUpdated(function (Get $get, Set $set) {
                $set('importe', (float) $get('cantidad') * (float) $get('precio_unitario'));
            }),

        TextInput::make('importe')
            ->numeric()
            ->disabled()
            ->dehydrated(true),
    ])->columns(4);
}

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('producto.nombre')->label('Producto')->searchable(),
                Tables\Columns\TextColumn::make('cantidad')->sortable(),
                Tables\Columns\TextColumn::make('precio_unitario')->sortable(),
                Tables\Columns\TextColumn::make('importe')->sortable(),
            ])
            ->headerActions([
                Actions\CreateAction::make(),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ]);
    }
}
