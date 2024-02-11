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
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->dateTime('data_fabricacao');
            $table->date('data_validade');
            $table->float('preco_custo');
            $table->float('preco_venda')->default(0);
            $table->boolean('vencido')->default(false);
            $table->unsignedBigInteger('produto_id');
            $table->foreign('produto_id')->on('produtos')->references('id')->onDelete('cascade');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->on('users')->references('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lotes');
    }
};
