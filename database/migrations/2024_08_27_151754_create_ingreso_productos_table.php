<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngresoProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingreso_productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("tipo_producto_id");
            $table->unsignedBigInteger("producto_id");
            $table->integer("cantidad");
            $table->text("descripcion")->nullable();
            $table->date("fecha_ingreso");
            $table->date("fecha_registro");
            $table->timestamps();

            $table->foreign("tipo_producto_id")->on("tipo_productos")->references("id");
            $table->foreign("producto_id")->on("productos")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingreso_productos');
    }
}
