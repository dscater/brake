<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    use HasFactory;

    protected $fillable = [
        "fecha",
        "categoria_id",
        "fecha_registro",
    ];

    protected $appends = ["fecha_t", "fecha_registro_t"];

    public function getFechaTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha));
    }

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    // RELACIONES
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function ingreso_detalles()
    {
        return $this->hasMany(IngresoDetalle::class, 'ingreso_id');
    }
}
