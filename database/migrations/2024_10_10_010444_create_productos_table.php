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
        Schema::create('productos', function (Blueprint $table) {
            $table->uuid(column: 'id')->primary();
            $table->string('nombre');
            $table->text('descripcion');
            $table->integer('precio');
            $table->integer('cantidad')->default(0); 
            $table->boolean('disponibilidad')->default(true);
            $table->string('imagen')->default('productos/producto-default.png');

            $table->uuid('vendedor_id');
            $table->foreign('vendedor_id')->references('id')->on('vendedores')->onDelete('cascade');
            $table->uuid('categoria_id');
            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
