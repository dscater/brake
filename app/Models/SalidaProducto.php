<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalidaProducto extends Model
{
    use HasFactory;
    protected $fillable = [
        "tipo_producto_id",
        "producto_id",
        "cantidad",
        "descripcion",
        "fecha_salida",
        "fecha_registro",
    ];
    
    protected $appends = ["fecha_registro_t","fecha_salida_t"];

    public function getFechaRegistroTAttribute(){
        return date("d/m/Y",strtotime($this->fecha_registro));
    }

    public function getFechaSalidaTAttribute(){
        return date("d/m/Y",strtotime($this->fecha_salida));
    }

    public function tipo_producto(){
        return $this->belongsTo(TipoProducto::class,'tipo_producto_id');
    }

    public function producto(){
        return $this->belongsTo(Producto::class,'producto_id');
    }
}
