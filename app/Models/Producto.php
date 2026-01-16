<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'categoria_id',
        'nombre',
        'imagen',
        'precio',
        'stock',
        'activo',
    ];
public function categoria()
{
    return $this->belongsTo(Categoria::class);
}

}
