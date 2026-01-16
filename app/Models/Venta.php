<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Venta extends Model
{
    protected $fillable = [
        'user_id',
        'subtotal',
        'descuento',
        'total',
        'metodo_pago',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleVenta::class);
    }
    public function recalcularTotales(): void
{
    $subtotal = $this->detalles()->sum('importe');
    $descuento = (float) ($this->descuento ?? 0);
    $total = max($subtotal - $descuento, 0);

    $this->update([
        'subtotal' => $subtotal,
        'total' => $total,
    ]);
}
}
