<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngresoDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        "concepto_id",
        "descripcion",
        "cantidad",
        "monto",
    ];

    public function concepto()
    {
        return $this->belongsTo(Concepto::class, 'concepto_id');
    }
}
