<?php

namespace App\Filament\Resources\Ventas\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
class VentaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
            Select::make('user_id')
                ->relationship('user', 'name')
                ->required(),

            DateTimePicker::make('fecha')
                ->default(now())
                ->required(),

            Select::make('metodo_pago')
                ->options([
                    'efectivo' => 'Efectivo',
                    'tarjeta' => 'Tarjeta',
                    'qr' => 'QR',
                ])
                ->default('efectivo')
                ->required(),

            TextInput::make('subtotal')->numeric()->default(0)->required(),
            TextInput::make('descuento')->numeric()->default(0)->required(),
            TextInput::make('total')->numeric()->default(0)->required(),
        ]);
    
    }
}
