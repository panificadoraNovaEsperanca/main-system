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
        Schema::create('rastreabilidade_lotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produto_rastreavel_id');
            $table->string('lote', 4);
            $table->date('data_producao');
            $table->string('responsavel')->nullable();
            $table->boolean('nao_produzido')->default(false);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('produto_rastreavel_id')->references('id')->on('produtos_rastreaveis')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->unique(['produto_rastreavel_id', 'lote', 'data_producao']);
            $table->index('data_producao');
            $table->index('lote');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rastreabilidade_lotes');
    }
};
