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
        Schema::table('rastreabilidade_lotes', function (Blueprint $table) {
            $table->timestamp('ultima_edicao_em')->nullable()->after('updated_at');
            $table->unsignedBigInteger('ultima_edicao_por')->nullable()->after('ultima_edicao_em');
            
            $table->foreign('ultima_edicao_por')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rastreabilidade_lotes', function (Blueprint $table) {
            $table->dropForeign(['ultima_edicao_por']);
            $table->dropColumn(['ultima_edicao_em', 'ultima_edicao_por']);
        });
    }
};
