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
        Schema::create('conceptos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("categoria_id");
            $table->string("nombre", 255);
            $table->string("descripcion", 600)->nullable();
            $table->date("fecha_registro")->nullable();
            $table->timestamps();

            $table->foreign("categoria_id")->on("categorias")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conceptos');
    }
};
