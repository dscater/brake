<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concepto extends Model
{
    use HasFactory;

    protected $fillable = [
        "categoria_id",
        "nombre",
        "descripcion",
        "fecha_registro",
    ];
    protected $appends = ["fecha_registro_t"];

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }
    
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
