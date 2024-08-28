<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        "tipo_producto_id",
        "nombre",
        "unidad",
        "precio",
        "stock_actual",
        "fecha_registro",
    ];

    protected $appends = ["fecha_registro_t"];

    public function getFechaRegistroTAttribute(){
        return date("d/m/Y",strtotime($this->fecha_registro));
    }

    public function tipo_producto()
    {
        return $this->belongsTo(TipoProducto::class, 'tipo_producto_id');
    }

       // FUNCIONES PARA INCREMETAR Y DECREMENTAR EL STOCK
       public static function incrementarStock($producto, $cantidad)
       {
           $producto->stock_actual = (float)$producto->stock_actual + $cantidad;
           $producto->save();
   
           $fecha_actual = date("Y-m-d");
           return true;
       }
       public static function decrementarStock($producto, $cantidad)
       {
           $producto->stock_actual = (float)$producto->stock_actual - $cantidad;
           $producto->save();
   
           $fecha_actual = date("Y-m-d");
           return true;
       }
}
