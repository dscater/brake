<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IngresoProducto extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        "tipo_producto_id",
        "producto_id",
        "cantidad",
        "descripcion",
        "fecha_ingreso",
        "fecha_registro",
    ];

    protected $appends = ["fecha_registro_t", "fecha_ingreso_t"];

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    public function getFechaIngresoTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_ingreso));
    }

    public function tipo_producto()
    {
        return $this->belongsTo(TipoProducto::class, 'tipo_producto_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
