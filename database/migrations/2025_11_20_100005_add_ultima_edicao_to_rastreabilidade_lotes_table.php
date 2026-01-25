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
        // Verificar se a tabela existe antes de tentar alterá-la
        if (!Schema::hasTable('rastreabilidade_lotes')) {
            return; // Tabela ainda não existe, pular esta migration
        }
        
        Schema::table('rastreabilidade_lotes', function (Blueprint $table) {
            // Verificar se as colunas já existem antes de adicionar
            if (!Schema::hasColumn('rastreabilidade_lotes', 'ultima_edicao_em')) {
                $table->timestamp('ultima_edicao_em')->nullable();
            }
            if (!Schema::hasColumn('rastreabilidade_lotes', 'ultima_edicao_por')) {
                $table->unsignedBigInteger('ultima_edicao_por')->nullable();
            }
        });
        
        // Adicionar foreign key separadamente após criar as colunas
        if (Schema::hasTable('rastreabilidade_lotes') && Schema::hasColumn('rastreabilidade_lotes', 'ultima_edicao_por')) {
            try {
                // Verificar se a foreign key já existe
                $constraintExists = DB::select("
                    SELECT constraint_name 
                    FROM information_schema.table_constraints 
                    WHERE table_schema = 'public'
                    AND table_name = 'rastreabilidade_lotes' 
                    AND constraint_type = 'FOREIGN KEY' 
                    AND constraint_name LIKE '%ultima_edicao_por%'
                ");
                
                if (empty($constraintExists)) {
                    DB::statement('ALTER TABLE rastreabilidade_lotes ADD CONSTRAINT rastreabilidade_lotes_ultima_edicao_por_foreign FOREIGN KEY (ultima_edicao_por) REFERENCES users(id) ON DELETE SET NULL');
                }
            } catch (\Exception $e) {
                // Ignorar se já existir ou houver erro
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('rastreabilidade_lotes')) {
            return;
        }
        
        // Remover foreign key primeiro
        try {
            DB::statement('ALTER TABLE rastreabilidade_lotes DROP CONSTRAINT IF EXISTS rastreabilidade_lotes_ultima_edicao_por_foreign');
        } catch (\Exception $e) {
            // Ignorar se não existir
        }
        
        Schema::table('rastreabilidade_lotes', function (Blueprint $table) {
            if (Schema::hasColumn('rastreabilidade_lotes', 'ultima_edicao_em')) {
                $table->dropColumn('ultima_edicao_em');
            }
            if (Schema::hasColumn('rastreabilidade_lotes', 'ultima_edicao_por')) {
                $table->dropColumn('ultima_edicao_por');
            }
        });
    }
};
