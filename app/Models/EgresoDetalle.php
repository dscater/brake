<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EgresoDetalle extends Model
{
    use HasFactory;
    protected $fillable = [
        "egreso_id",
        "concepto_id",
        "descripcion",
        "cantidad",
        "monto",
    ];

    public function egreso()
    {
        return $this->belongsTo(Egreso::class, 'egreso_id');
    }

    public function concepto()
    {
        return $this->belongsTo(Concepto::class, 'concepto_id');
    }
}
