<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class HistorialAccion extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "role",
        "accion",
        "descripcion",
        "datos_original",
        "datos_nuevo",
        "modulo",
        "tabla",
        "fecha",
        "hora",
        "sistema",
        "ip",
    ];


    protected $casts = [
        'datos_original' => 'array',
        'datos_nuevo' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function getDetalleRegistro($item, $table)
    {
        $columns = Schema::getColumnListing($table);
        $datos = "";
        foreach ($columns as $key => $col) {
            $datos .= $col . ": " . $item[$col];
            if ($key < count($columns)) {
                $datos .= '<br/>';
            }
        }
        return $datos;
    }
}
