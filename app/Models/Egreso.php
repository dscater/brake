<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
    use HasFactory;

    protected $fillable = [
        "fecha",
        "categoria_id",
        "fecha_registro",
    ];

    protected $appends = ["fecha_t", "fecha_registro_t", "total_c", "total_m", "total_m_txt"];

    public function getTotalCAttribute()
    {
        $total = EgresoDetalle::where("egreso_id", $this->id)->sum("cantidad");
        return $total;
    }
    public function getTotalMAttribute()
    {
        $total = EgresoDetalle::where("egreso_id", $this->id)->sum("monto");
        return $total;
    }

    public function getTotalMTxtAttribute()
    {
        $total = EgresoDetalle::where("egreso_id", $this->id)->sum("monto");
        return number_format($total, 2, ".", ",");
    }

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

    public function egreso_detalles()
    {
        return $this->hasMany(EgresoDetalle::class, 'egreso_id');
    }
}
