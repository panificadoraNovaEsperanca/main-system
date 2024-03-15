<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;

class InitSystem extends Command
{
    protected $signature = 'inicializar:sistema';
    protected $description = 'Inicializa o sistema';


    public function handle()
    {
        if (DB::table('users')->count() === 0) {
            Artisan::call('migrate:fresh --seed --force');
        } else {
            echo "O banco de dados não está vazio. A migração fresca não será executada.";
        }
    }


}