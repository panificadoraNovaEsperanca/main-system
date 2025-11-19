<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Popular TODOS os produtos com quantidade_embalagem = 50
        // O usuário pode alterar depois conforme necessário
        DB::table('produtos')->update(['quantidade_embalagem' => 50]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reverter: definir quantidade_embalagem como NULL para produtos que tinham 50
        // (apenas se não houver outros valores)
        DB::table('produtos')
            ->where('quantidade_embalagem', 50)
            ->update(['quantidade_embalagem' => null]);
    }
};
