<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'categorias',
            'conceptos',
            'egresos',
            'egreso_detalles',
            'ingresos',
            'ingreso_detalles',
            'ingreso_productos',
            'kardex_productos',
            'productos',
            'salida_productos',
            'tipo_productos',
            'users',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->softDeletes(); // crea la columna deleted_at
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'categorias',
            'conceptos',
            'egresos',
            'egreso_detalles',
            'ingresos',
            'ingreso_detalles',
            'ingreso_productos',
            'kardex_productos',
            'productos',
            'salida_productos',
            'tipo_productos',
            'users',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropSoftDeletes(); // elimina la columna deleted_at
            });
        }
    }
};
