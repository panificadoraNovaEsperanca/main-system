<?php

namespace Database\Seeders;

use App\Enums\TipoLancamento;
use App\Models\Categoria;
use App\Models\Fornecedor;
use App\Models\Lancamento;
use App\Models\Lote;
use App\Models\Marca;
use App\Models\Produto;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FillBasicData extends Seeder
{
    /**
     * Run the database seeds
     *
     * @return void
     */
    public function run()
    {
        $data =
            [
                'name' => 'Heryck',
                'email' => 'heryck@email.com',
                'email_verified_at' => now(),
                'password' => Hash::make('heryck172020'), // password

            ];
        User::create($data);
    }
}
