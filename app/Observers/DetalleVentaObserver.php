<?php

namespace App\Observers;

use App\Models\DetalleVenta;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class DetalleVentaObserver
{
    public function creating(DetalleVenta $detalle): void
    {
        DB::transaction(function () use ($detalle) {
            $producto = Producto::lockForUpdate()->findOrFail($detalle->producto_id);

            $cantidad = (int) $detalle->cantidad;

            if ($producto->stock < $cantidad) {
                throw new \RuntimeException("Stock insuficiente para {$producto->nombre}. Disponible: {$producto->stock}");
            }

            // calcular importe si no viene
            $detalle->importe = $cantidad * (float) $detalle->precio_unitario;

            // descontar stock
            $producto->decrement('stock', $cantidad);
        });
    }

    public function updating(DetalleVenta $detalle): void
    {
        DB::transaction(function () use ($detalle) {
            // Recalcular importe
            $detalle->importe = (int) $detalle->cantidad * (float) $detalle->precio_unitario;

            // Si cambió producto o cantidad, ajustar stock con diferencia
            $originalProductoId = (int) $detalle->getOriginal('producto_id');
            $originalCantidad   = (int) $detalle->getOriginal('cantidad');

            $nuevoProductoId = (int) $detalle->producto_id;
            $nuevaCantidad   = (int) $detalle->cantidad;

            // Caso 1: mismo producto -> ajustar diferencia
            if ($originalProductoId === $nuevoProductoId) {
                $diff = $nuevaCantidad - $originalCantidad; // + = descontar más, - = devolver stock

                if ($diff !== 0) {
                    $producto = Producto::lockForUpdate()->findOrFail($nuevoProductoId);

                    if ($diff > 0 && $producto->stock < $diff) {
                        throw new \RuntimeException("Stock insuficiente para {$producto->nombre}. Disponible: {$producto->stock}");
                    }

                    // diff positivo descuenta, negativo devuelve
                    $producto->stock = $producto->stock - $diff;
                    $producto->save();
                }

                return;
            }

            // Caso 2: cambió de producto -> devolver stock al viejo y descontar del nuevo
            $productoViejo = Producto::lockForUpdate()->findOrFail($originalProductoId);
            $productoViejo->increment('stock', $originalCantidad);

            $productoNuevo = Producto::lockForUpdate()->findOrFail($nuevoProductoId);
            if ($productoNuevo->stock < $nuevaCantidad) {
                throw new \RuntimeException("Stock insuficiente para {$productoNuevo->nombre}. Disponible: {$productoNuevo->stock}");
            }
            $productoNuevo->decrement('stock', $nuevaCantidad);
        });
    }

    public function deleting(DetalleVenta $detalle): void
    {
        DB::transaction(function () use ($detalle) {
            // devolver stock
            $producto = Producto::lockForUpdate()->findOrFail($detalle->producto_id);
            $producto->increment('stock', (int) $detalle->cantidad);
        });
    }

    public function created(DetalleVenta $detalle): void
    {
        $detalle->venta?->recalcularTotales();
    }

    public function updated(DetalleVenta $detalle): void
    {
        $detalle->venta?->recalcularTotales();
    }

    public function deleted(DetalleVenta $detalle): void
    {
        $detalle->venta?->recalcularTotales();
    }
}
