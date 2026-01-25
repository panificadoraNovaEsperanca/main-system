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
        Schema::create('rastreabilidade_ingredientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rastreabilidade_lote_id');
            $table->unsignedBigInteger('insumo_id');
            $table->string('marca')->nullable();
            $table->string('lote_ingrediente')->nullable();
            $table->date('validade_original')->nullable();
            $table->date('data_abertura')->nullable();
            $table->date('validade_apos_aberto')->nullable();
            $table->string('caracteristica_sensorial')->default('Conforme');
            $table->timestamps();

            $table->foreign('rastreabilidade_lote_id')->references('id')->on('rastreabilidade_lotes')->onDelete('cascade');
            $table->foreign('insumo_id')->references('id')->on('insumos')->onDelete('cascade');
            $table->index('rastreabilidade_lote_id');
            $table->index('insumo_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rastreabilidade_ingredientes');
    }
};
