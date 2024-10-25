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
        Schema::table('ventas', function (Blueprint $table) {
            $table->integer('cantidad');
            $table->boolean('entrega_domicilio')->default(false);
            $table->string('lugar_entrega');
            $table->string('metodo');
            $table->string('comprobante')->nullable();
            $table->integer('valor');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn('cantidad');
            $table->dropColumn('entrega_domicilio');
            $table->dropColumn('lugar_entrega');
            $table->dropColumn('metodo');
            $table->dropColumn('comprobante');
            $table->dropColumn('valor');

        });
    }
};
