<?php

namespace App\Filament\Resources\Categorias\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class CategoriaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                ->label('Nombre')
                ->required()
                ->maxLength(255),

            Toggle::make('activo')
                ->label('Activo')
                ->default(true),
            ]);
    }
}
