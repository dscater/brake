<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngresoDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        "ingreso_id",
        "concepto_id",
        "descripcion",
        "cantidad",
        "monto",
    ];

    protected $appends = ["monto_txt"];

    public function getMontoTxtAttribute()
    {
        return number_format($this->monto, 2, ".", ",");
    }

    public function ingreso()
    {
        return $this->belongsTo(Ingreso::class, 'ingreso_id');
    }

    public function concepto()
    {
        return $this->belongsTo(Concepto::class, 'concepto_id');
    }
}
