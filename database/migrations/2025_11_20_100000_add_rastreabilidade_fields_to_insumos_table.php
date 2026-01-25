<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insumos', function (Blueprint $table) {
            if (!Schema::hasColumn('insumos', 'marca')) {
                $table->string('marca')->nullable()->after('quantidade_minima');
            }
            if (!Schema::hasColumn('insumos', 'lote_ingrediente')) {
                $table->string('lote_ingrediente')->nullable()->after('marca');
            }
            if (!Schema::hasColumn('insumos', 'validade_original')) {
                $table->date('validade_original')->nullable()->after('lote_ingrediente');
            }
            if (!Schema::hasColumn('insumos', 'data_abertura')) {
                $table->date('data_abertura')->nullable()->after('validade_original');
            }
            if (!Schema::hasColumn('insumos', 'validade_apos_aberto')) {
                $table->date('validade_apos_aberto')->nullable()->after('data_abertura');
            }
            if (!Schema::hasColumn('insumos', 'caracteristica_sensorial')) {
                $table->string('caracteristica_sensorial')->default('Conforme')->nullable()->after('validade_apos_aberto');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('insumos', function (Blueprint $table) {
            $table->dropColumn([
                'marca',
                'lote_ingrediente',
                'validade_original',
                'data_abertura',
                'validade_apos_aberto',
                'caracteristica_sensorial'
            ]);
        });
    }
};
