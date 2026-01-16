<?php

namespace App\Filament\Resources\Productos\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
class ProductoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('categoria_id')
                ->relationship('categoria', 'nombre')
                ->label('Categoría')
                ->required(),

            TextInput::make('nombre')
                ->label('Nombre del producto')
                ->required()
                ->maxLength(255),

                  FileUpload::make('imagen')
            ->label('Imagen')
    ->image()
    ->disk('public')
    ->directory('productos')
    ->visibility('public')
    ->preserveFilenames()
    ->imageEditor()
    ->maxSize(2048)
    ->nullable(),

            TextInput::make('precio')
                ->label('Precio')
                ->numeric()
                ->required(),


            TextInput::make('stock')
                ->label('Stock')
                ->numeric()
                ->default(0)
                ->required(),

            Toggle::make('activo')
                ->label('Activo')
                ->default(true),
            ]);
    }
}
