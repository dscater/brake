<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EgresoDetalle extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "egreso_id",
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

    public function egreso()
    {
        return $this->belongsTo(Egreso::class, 'egreso_id');
    }

    public function concepto()
    {
        return $this->belongsTo(Concepto::class, 'concepto_id');
    }
}
