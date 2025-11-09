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
        Schema::table('produtos', function (Blueprint $table) {
            // Remove as colunas antigas de setor
            $table->dropColumn(['setor', 'setor_1', 'setor_2', 'setor_3']);
            
            // Adiciona a coluna setor_id como foreign key
            $table->unsignedBigInteger('setor_id')->nullable();
            $table->foreign('setor_id')->references('id')->on('setores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('produtos', function (Blueprint $table) {
            // Remove a foreign key e a coluna setor_id
            $table->dropForeign(['setor_id']);
            $table->dropColumn('setor_id');
            
            // Recria as colunas antigas
            $table->string('setor', 255)->nullable();
            $table->string('setor_1', 255)->nullable();
            $table->string('setor_2', 255)->nullable();
            $table->string('setor_3', 255)->nullable();
        });
    }
};
