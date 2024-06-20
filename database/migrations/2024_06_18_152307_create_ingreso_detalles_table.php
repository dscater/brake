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
        Schema::create('ingreso_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("ingreso_id");
            $table->unsignedBigInteger("concepto_id");
            $table->string("descripcion", 600);
            $table->double("cantidad", 8, 2);
            $table->decimal("monto", 24, 2);
            $table->timestamps();

            $table->foreign("ingreso_id")->on("ingresos")->references("id");
            $table->foreign("concepto_id")->on("conceptos")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingreso_detalles');
    }
};
