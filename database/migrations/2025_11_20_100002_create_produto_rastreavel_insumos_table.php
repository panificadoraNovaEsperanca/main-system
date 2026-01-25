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
        Schema::create('produto_rastreavel_insumos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produto_rastreavel_id');
            $table->unsignedBigInteger('insumo_id');
            $table->integer('ordem')->default(0);
            $table->timestamps();

            $table->foreign('produto_rastreavel_id')->references('id')->on('produtos_rastreaveis')->onDelete('cascade');
            $table->foreign('insumo_id')->references('id')->on('insumos')->onDelete('cascade');
            $table->unique(['produto_rastreavel_id', 'insumo_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produto_rastreavel_insumos');
    }
};
