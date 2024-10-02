<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('producaos', function (Blueprint $table) {
            $table->string('turno')->nullable();
        });
    }

    public function down()
    {
        Schema::table('table_name', function (Blueprint $table) {
            $table->dropColumn('turno');; // Reverte para o tipo original se necessário
        });
    }
};
