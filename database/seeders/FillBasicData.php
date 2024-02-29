<?php

namespace Database\Seeders;

use App\Enums\TipoLancamento;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Fornecedor;
use App\Models\Lancamento;
use App\Models\Lote;
use App\Models\Marca;
use App\Models\Motorista;
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
                'name' => 'Administrador',
                'email' => 'administrador@email.com',
                'email_verified_at' => now(),
                'password' => Hash::make('mudar@123'), // password

            ];
        User::create($data);
        $arrayProdutos = [];
        $arrayProdutos[] = ['nome' => 'Pão Francês', 'unidade' => 'Und', 'precos' => [
            'a' => 0.50,
            'b' => 0.55,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.65
        ]];
        $arrayProdutos[] = ['nome' => 'Pão Francês embalado individual', 'unidade' => 'Und', 'precos' => [
            'a' => 0.65,
            'b' => 0.70,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.80
        ]];
        $arrayProdutos[] = ['nome' => 'Pão Francês Integral', 'unidade' => 'Und', 'precos' => [
            'a' => 0.70,
            'b' => 0.70,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.75
        ]];
        $arrayProdutos[] = ['nome' => 'Pão Francês Integral embalado individual', 'unidade' => 'Und', 'precos' => [
            'a' => 0.85,
            'b' => 0.85,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.85
        ]];
        $arrayProdutos[] = ['nome' => 'Pão de Leite', 'unidade' => 'Und', 'precos' => [
            'a' => 0.55,
            'b' => 0.60,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.70
        ]];
        $arrayProdutos[] = ['nome' => 'Pão de Leite embalado individual', 'unidade' => 'Und', 'precos' => [
            'a' => 0.70,
            'b' => 0.75,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.85
        ]];
        $arrayProdutos[] = ['nome' => 'Pão de Leite Integral', 'unidade' => 'Und', 'precos' => [
            'a' => 0.70,
            'b' => 0.70,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.75
        ]];
        $arrayProdutos[] = ['nome' => 'Pão de Leite Integral embalado individual ', 'unidade' => 'Und', 'precos' => [
            'a' => 0.85,
            'b' => 0.85,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.85
        ]];
        $arrayProdutos[] = ['nome' => 'Pão de Milho', 'unidade' => 'Und', 'precos' => [
            'a' => 0.65,
            'b' => 0.70,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.75
        ]];
        $arrayProdutos[] = ['nome' => 'Pão de Miho embalado individual', 'unidade' => 'Und', 'precos' => [
            'a' => 0.80,
            'b' => 0.85,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.90
        ]];
        $arrayProdutos[] = ['nome' => 'Pão de Coco', 'unidade' => 'Und', 'precos' => [
            'a' => 0.65,
            'b' => 0.70,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.75
        ]];
        $arrayProdutos[] = ['nome' => 'Pão de Coco embalado individual', 'unidade' => 'Und', 'precos' => [
            'a' => 0.80,
            'b' => 0.85,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.90
        ]];
        $arrayProdutos[] = ['nome' => 'Pão de Creme', 'unidade' => 'Und', 'precos' => [
            'a' => 0.65,
            'b' => 0.70,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.75
        ]];
        $arrayProdutos[] = ['nome' => 'Pão de Creme embalado individual', 'unidade' => 'Und', 'precos' => [
            'a' => 0.80,
            'b' => 0.85,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.90
        ]];
        $arrayProdutos[] = ['nome' => 'Pão de Batata', 'unidade' => 'Und', 'precos' => [
            'a' => 0.80,
            'b' => 0.80,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.80
        ]];
        $arrayProdutos[] = ['nome' => 'Pão de Mandioquinha', 'unidade' => 'Und', 'precos' => [
            'a' => 0.80,
            'b' => 0.80,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.80
        ]];
        $arrayProdutos[] = ['nome' => 'Pão de Mandioquinha embalado individual', 'unidade' => 'Und', 'precos' => [
            'a' => 0.95,
            'b' => 0.95,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.95
        ]];
        $arrayProdutos[] = ['nome' => 'Pão francês com margarina ', 'unidade' => 'Und', 'precos' => [
            'a' => 1.00,
            'b' => 1.10,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 1.20
        ]];
        $arrayProdutos[] = ['nome' => 'Pão de Leite com margarina ', 'unidade' => 'Und', 'precos' => [
            'a' => 1.00,
            'b' => 1.10,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 1.20
        ]];
        $arrayProdutos[] = ['nome' => 'Pão de Milho com margarina', 'unidade' => 'Und', 'precos' => [
            'a' => 1.00,
            'b' => 1.10,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 1.20
        ]];
        $arrayProdutos[] = ['nome' => 'Pão de Creme com margarina', 'unidade' => 'Und', 'precos' => [
            'a' => 1.00,
            'b' => 1.10,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 1.20
        ]];
        $arrayProdutos[] = ['nome' => 'Pão de Cenoura com margarina', 'unidade' => 'Und', 'precos' => [
            'a' => 1.00,
            'b' => 1.10,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 1.20
        ]];
        $arrayProdutos[] = ['nome' => 'Pão com frios', 'unidade' => 'Und', 'precos' => [
            'a' => 3.50,
            'b' => 3.75,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 3.75
        ]];
        $arrayProdutos[] = ['nome' => 'Pão com mortadela', 'unidade' => 'Und', 'precos' => [
            'a' => 3.25,
            'b' => 3.50,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 3.50
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Pão Francês', 'unidade' => 'Und', 'precos' => [
            'a' => 0.40,
            'b' => 0.40,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.40
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Pão Francês embalado individual', 'unidade' => 'Und', 'precos' => [
            'a' => 0.55,
            'b' => 0.55,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.55
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Pão de Leite', 'unidade' => 'Und', 'precos' => [
            'a' => 0.40,
            'b' => 0.40,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.40
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Pão de Leite embalado individual', 'unidade' => 'Und', 'precos' => [
            'a' => 0.55,
            'b' => 0.55,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.55
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Pão de Batata', 'unidade' => 'Und', 'precos' => [
            'a' => 0.55,
            'b' => 0.55,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.55
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Pão de Batata embalado individual', 'unidade' => 'Und', 'precos' => [
            'a' => 0.70,
            'b' => 0.70,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.70
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Pão de Mandioquinha', 'unidade' => 'Und', 'precos' => [
            'a' => 0.55,
            'b' => 0.55,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.55
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Pão de Mandioquinha embalado individual', 'unidade' => 'Und', 'precos' => [
            'a' => 0.70,
            'b' => 0.70,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.70
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Pão de Cenoura', 'unidade' => 'Und', 'precos' => [
            'a' => 0.55,
            'b' => 0.55,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.55
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Pão de Cenoura embalado individual', 'unidade' => 'Und', 'precos' => [
            'a' => 0.70,
            'b' => 0.70,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.70
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Pão de Brioche', 'unidade' => 'Und', 'precos' => [
            'a' => 0.75,
            'b' => 0.75,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.75
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Pão de Brioche embalado individual', 'unidade' => 'Und', 'precos' => [
            'a' => 0.90,
            'b' => 0.90,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.90
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Pão Australiano', 'unidade' => 'Und', 'precos' => [
            'a' => 0.75,
            'b' => 0.75,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.75
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Pão Australiano embalado individual', 'unidade' => 'Und', 'precos' => [
            'a' => 0.90,
            'b' => 0.90,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.90
        ]];
        $arrayProdutos[] = ['nome' => 'Bisnaguinha pct 300g', 'unidade' => 'Pct', 'precos' => [
            'a' => 4.50,
            'b' => 4.50,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 4.50
        ]];
        $arrayProdutos[] = ['nome' => 'Bisnaguinha Integral pct 300g', 'unidade' => 'Pct', 'precos' => [
            'a' => 5.00,
            'b' => 5.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 5.00
        ]];
        $arrayProdutos[] = ['nome' => 'Hotdog 50g', 'unidade' => 'Und', 'precos' => [
            'a' => 0.55,
            'b' => 0.55,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.55
        ]];
        $arrayProdutos[] = ['nome' => 'Hotdog Integral 50g', 'unidade' => 'Und', 'precos' => [
            'a' => 0.65,
            'b' => 0.65,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.65
        ]];
        $arrayProdutos[] = ['nome' => 'Hotdog 80g', 'unidade' => 'Und', 'precos' => [
            'a' => 0.80,
            'b' => 0.80,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.80
        ]];
        $arrayProdutos[] = ['nome' => 'Hambúrguer tradicional aro 10 - 50g', 'unidade' => 'Und', 'precos' => [
            'a' => 0.85,
            'b' => 0.85,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.85
        ]];
        $arrayProdutos[] = ['nome' => 'Hambúrguer tradicional aro 13 - 80g', 'unidade' => 'Und', 'precos' => [
            'a' => 1.25,
            'b' => 1.25,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 1.25
        ]];
        $arrayProdutos[] = ['nome' => 'Hambúrguer brioche aro 10 - 50g', 'unidade' => 'Und', 'precos' => [
            'a' => 1.25,
            'b' => 1.25,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 1.25
        ]];
        $arrayProdutos[] = ['nome' => 'Hambúrguer brioche aro 13 - 80g', 'unidade' => 'Und', 'precos' => [
            'a' => 1.75,
            'b' => 1.75,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 1.75
        ]];
        $arrayProdutos[] = ['nome' => 'Hambúrguer australiano aro 10 - 50g', 'unidade' => 'Und', 'precos' => [
            'a' => 1.25,
            'b' => 1.25,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 1.25
        ]];
        $arrayProdutos[] = ['nome' => 'Hambúrguer australiano aro 13 80g', 'unidade' => 'Und', 'precos' => [
            'a' => 1.75,
            'b' => 1.75,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 1.75
        ]];
        $arrayProdutos[] = ['nome' => 'Sovado 500g', 'unidade' => 'Und', 'precos' => [
            'a' => 4.90,
            'b' => 4.90,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 4.90
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Sovado 60g', 'unidade' => 'Und', 'precos' => [
            'a' => 1.00,
            'b' => 1.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 1.00
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Rosca Açúcarada 50g', 'unidade' => 'Und', 'precos' => [
            'a' => 1.20,
            'b' => 1.20,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 1.20
        ]];
        $arrayProdutos[] = ['nome' => 'Broa de Fubá pct 5 unidades', 'unidade' => 'Pct', 'precos' => [
            'a' => 10.00,
            'b' => 10.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 10.00
        ]];
        $arrayProdutos[] = ['nome' => 'Broa de Coco pct 5 unidades', 'unidade' => 'Pct', 'precos' => [
            'a' => 10.00,
            'b' => 10.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 10.00
        ]];
        $arrayProdutos[] = ['nome' => 'Broa de Fubá com gotas pct 5 unidades', 'unidade' => 'Pct', 'precos' => [
            'a' => 12.00,
            'b' => 12.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 12.00
        ]];
        $arrayProdutos[] = ['nome' => 'Farinha de Rosca', 'unidade' => 'KG', 'precos' => [
            'a' => 6.00,
            'b' => 6.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 6.00
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Croissant', 'unidade' => 'Und', 'precos' => [
            'a' => 1.00,
            'b' => 1.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 1.00
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Carolina Doce de Leite', 'unidade' => 'Und', 'precos' => [
            'a' => 1.00,
            'b' => 1.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 1.00
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Sonho ', 'unidade' => 'Und', 'precos' => [
            'a' => 1.50,
            'b' => 1.50,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 1.50
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Salgado Assado', 'unidade' => 'Und', 'precos' => [
            'a' => 0.90,
            'b' => 0.90,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.90
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Salgado Frito', 'unidade' => 'Und', 'precos' => [
            'a' => 0.90,
            'b' => 0.90,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 0.90
        ]];
        $arrayProdutos[] = ['nome' => 'Lanche de Metro Presunto e Queijo', 'unidade' => 'Und', 'precos' => [
            'a' => 65.00,
            'b' => 65.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 65.00
        ]];
        $arrayProdutos[] = ['nome' => 'Lanche de Metro Peito de Peru e Queijo Minas', 'unidade' => 'Und', 'precos' => [
            'a' => 70.00,
            'b' => 70.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 70.00
        ]];
        $arrayProdutos[] = ['nome' => 'Lanche de Metro Salame e Queijo Cheddar', 'unidade' => 'Und', 'precos' => [
            'a' => 80.00,
            'b' => 80.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 80.00
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Lanche no Pão de Brioche', 'unidade' => 'Und', 'precos' => [
            'a' => 1.80,
            'b' => 1.80,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 1.80
        ]];
        $arrayProdutos[] = ['nome' => 'Mini Lanche no Pão de Leite', 'unidade' => 'Und', 'precos' => [
            'a' => 1.60,
            'b' => 1.60,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 1.60
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo Confeitado forma 4 (30 fatias)', 'unidade' => 'Und', 'precos' => [
            'a' => 160.00,
            'b' => 160.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 160.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo Confeitado forma 5 (40 fatias)', 'unidade' => 'Und', 'precos' => [
            'a' => 200.00,
            'b' => 200.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 200.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo Confeitado forma 6 (60 fatias)', 'unidade' => 'Und', 'precos' => [
            'a' => 240.00,
            'b' => 240.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 240.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo Confeitado forma 7 (80 fatias)', 'unidade' => 'Und', 'precos' => [
            'a' => 280.00,
            'b' => 280.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 280.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo 500g - Coco', 'unidade' => 'Und', 'precos' => [
            'a' => 11.00,
            'b' => 11.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 11.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo 500g - Laranja', 'unidade' => 'Und', 'precos' => [
            'a' => 11.00,
            'b' => 11.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 11.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo 500g - Baunilha', 'unidade' => 'Und', 'precos' => [
            'a' => 11.00,
            'b' => 11.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 11.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo 500g - Limão', 'unidade' => 'Und', 'precos' => [
            'a' => 11.00,
            'b' => 11.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 11.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo 500g - Abacaxi', 'unidade' => 'Und', 'precos' => [
            'a' => 11.00,
            'b' => 11.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 11.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo 500g - Chocolate', 'unidade' => 'Und', 'precos' => [
            'a' => 11.00,
            'b' => 11.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 11.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo 500g - Formigueiro', 'unidade' => 'Und', 'precos' => [
            'a' => 11.00,
            'b' => 11.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 11.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo 500g - Mesclado', 'unidade' => 'Und', 'precos' => [
            'a' => 11.00,
            'b' => 11.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 11.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo 500g - Cenoura', 'unidade' => 'Und', 'precos' => [
            'a' => 11.00,
            'b' => 11.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 11.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo 500g - Cenoura com cobertura', 'unidade' => 'Und', 'precos' => [
            'a' => 16.00,
            'b' => 16.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 16.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo 1,500Kg - Coco', 'unidade' => 'Und', 'precos' => [
            'a' => 28.00,
            'b' => 28.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 28.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo 1,500Kg - Laranja', 'unidade' => 'Und', 'precos' => [
            'a' => 28.00,
            'b' => 28.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 28.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo 1,500Kg - Baunilha', 'unidade' => 'Und', 'precos' => [
            'a' => 28.00,
            'b' => 28.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 28.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo 1,500Kg - Limão', 'unidade' => 'Und', 'precos' => [
            'a' => 28.00,
            'b' => 28.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 28.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo 1,500Kg - Abacaxi', 'unidade' => 'Und', 'precos' => [
            'a' => 28.00,
            'b' => 28.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 28.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo 1,500Kg - Chocolate', 'unidade' => 'Und', 'precos' => [
            'a' => 28.00,
            'b' => 28.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 28.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo 1,500Kg - Formigueiro', 'unidade' => 'Und', 'precos' => [
            'a' => 28.00,
            'b' => 28.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 28.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo 1,500Kg - Mesclado', 'unidade' => 'Und', 'precos' => [
            'a' => 28.00,
            'b' => 28.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 28.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo 1,500Kg - Cenoura', 'unidade' => 'Und', 'precos' => [
            'a' => 28.00,
            'b' => 28.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 28.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo 1,500Kg - Cenoura com cobertura', 'unidade' => 'Und', 'precos' => [
            'a' => 33.00,
            'b' => 33.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 33.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo em Placa 4Kg - Coco', 'unidade' => 'Und', 'precos' => [
            'a' => 70.00,
            'b' => 70.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 70.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo em Placa 4Kg - Laranja', 'unidade' => 'Und', 'precos' => [
            'a' => 70.00,
            'b' => 70.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 70.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo em Placa 4Kg - Baunilha', 'unidade' => 'Und', 'precos' => [
            'a' => 70.00,
            'b' => 70.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 70.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo em Placa 4Kg - Limão', 'unidade' => 'Und', 'precos' => [
            'a' => 70.00,
            'b' => 70.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 70.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo em Placa 4Kg - Abacaxi', 'unidade' => 'Und', 'precos' => [
            'a' => 70.00,
            'b' => 70.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 70.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo em Placa 4Kg - Chocolate', 'unidade' => 'Und', 'precos' => [
            'a' => 70.00,
            'b' => 70.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 70.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo em Placa 4Kg - Formigueiro', 'unidade' => 'Und', 'precos' => [
            'a' => 70.00,
            'b' => 70.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 70.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo em Placa 4Kg - Mesclado', 'unidade' => 'Und', 'precos' => [
            'a' => 70.00,
            'b' => 70.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 70.00
        ]];
        $arrayProdutos[] = ['nome' => 'Bolo em Placa 4Kg - Cenoura', 'unidade' => 'Und', 'precos' => [
            'a' => 70.00,
            'b' => 70.00,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 70.00
        ]];
        $arrayProdutos[] = ['nome' => 'Pão Francês Congelado - Vermelho', 'unidade' => 'Pct', 'precos' => [
            'a' => 35.00,
            'b' => 41.30,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 42.00
        ]];
        $arrayProdutos[] = ['nome' => 'Pão Francês Congelado - Azul', 'unidade' => 'Pct', 'precos' => [
            'a' => 35.00,
            'b' => 41.30,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 42.00
        ]];
        $arrayProdutos[] = ['nome' => 'Pão de Leite Congelado - Amarelo', 'unidade' => 'Pct', 'precos' => [
            'a' => 35.00,
            'b' => 48.30,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 49.00
        ]];
        $arrayProdutos[] = ['nome' => 'Pão Francês Integral Congelado - Marrom', 'unidade' => 'Pct', 'precos' => [
            'a' => 42.00,
            'b' => 48.30,
            'd' => 0, 'e' => 0, 'f' => 0, 'g' => 0, 'h' => 0, 'c' => 49.00
        ]];
        foreach ($arrayProdutos as $produto) {
            Produto::create($produto);
        }
        $motoristas = [];
        $motoristas[]  = ['nome' => 'Alex MANHÃ', 'turno' => 'MANHÃ'];
        $motoristas[]  = ['nome' => 'Alex TARDE', 'turno' => 'TARDE'];
        $motoristas[]  = ['nome' => 'Alex NOITE', 'turno' => 'NOITE'];
        $motoristas[]  = ['nome' => 'Alex Sábado MANHÃ', 'turno' => 'MANHÃ'];
        $motoristas[]  = ['nome' => 'Alex Sábado TARDE', 'turno' => 'TARDE'];
        $motoristas[]  = ['nome' => 'Alex Sábado NOITE', 'turno' => 'NOITE'];
        $motoristas[]  = ['nome' => 'Alex Domingo MANHÃ', 'turno' => 'MANHÃ'];
        $motoristas[]  = ['nome' => 'Alex Domingo TARDE', 'turno' => 'TARDE'];
        $motoristas[]  = ['nome' => 'Alex Domingo NOITE', 'turno' => 'NOITE'];
        $motoristas[]  = ['nome' => 'Nei MANHÃ', 'turno' => 'MANHÃ'];
        $motoristas[]  = ['nome' => 'Nei TARDE', 'turno' => 'TARDE'];
        $motoristas[]  = ['nome' => 'Nei Sábado MANHÃ', 'turno' => 'MANHÃ'];
        $motoristas[]  = ['nome' => 'Nei Sábado TARDE', 'turno' => 'TARDE'];
        $motoristas[]  = ['nome' => 'Nei Domingo MANHÃ', 'turno' => 'MANHÃ'];
        $motoristas[]  = ['nome' => 'Nei Domingo TARDE', 'turno' => 'TARDE'];
        $motoristas[]  = ['nome' => 'Valdir MANHÃ', 'turno' => 'MANHÃ'];
        $motoristas[]  = ['nome' => 'Valdir TARDE', 'turno' => 'TARDE'];
        $motoristas[]  = ['nome' => 'Valdir NOITE', 'turno' => 'NOITE'];
        $motoristas[]  = ['nome' => 'Valdir Sábado MANHÃ', 'turno' => 'MANHÃ'];
        $motoristas[]  = ['nome' => 'Valdir Sábado TARDE', 'turno' => 'TARDE'];
        $motoristas[]  = ['nome' => 'Valdir Sábado NOITE', 'turno' => 'NOITE'];
        $motoristas[]  = ['nome' => 'Valdir Domingo MANHÃ', 'turno' => 'MANHÃ'];
        $motoristas[]  = ['nome' => 'Valdir Domingo TARDE', 'turno' => 'TARDE'];
        $motoristas[]  = ['nome' => 'Valdir Domingo NOITE', 'turno' => 'NOITE'];
        $motoristas[]  = ['nome' => 'André MANHÃ', 'turno' => 'MANHÃ'];
        $motoristas[]  = ['nome' => 'André TARDE', 'turno' => 'TARDE'];
        $motoristas[]  = ['nome' => 'André NOITE', 'turno' => 'NOITE'];
        $motoristas[]  = ['nome' => 'Nildo MANHÃ', 'turno' => 'MANHÃ'];
        $motoristas[]  = ['nome' => 'Nildo TARDE', 'turno' => 'TARDE'];
        $motoristas[]  = ['nome' => 'Nildo NOITE', 'turno' => 'NOITE'];
        $motoristas[]  = ['nome' => 'Nildo Sábado MANHÃ', 'turno' => 'MANHÃ'];
        $motoristas[]  = ['nome' => 'Nildo Sábado TARDE', 'turno' => 'TARDE'];
        $motoristas[]  = ['nome' => 'João Paulo MANHÃ', 'turno' => 'MANHÃ'];
        $motoristas[]  = ['nome' => 'João Paulo TARDE', 'turno' => 'TARDE'];
        $motoristas[]  = ['nome' => 'João Paulo NOITE', 'turno' => 'NOITE'];
        $motoristas[]  = ['nome' => 'João Paulo Sábado MANHÃ', 'turno' => 'MANHÃ'];
        $motoristas[]  = ['nome' => 'João Paulo Sábado TARDE', 'turno' => 'TARDE'];
        $motoristas[]  = ['nome' => 'João Paulo Sábado NOITE', 'turno' => 'NOITE'];
        $motoristas[]  = ['nome' => 'Arnaldo MANHÃ', 'turno' => 'MANHÃ'];
        $motoristas[]  = ['nome' => 'Arnaldo TARDE', 'turno' => 'TARDE'];
        $motoristas[]  = ['nome' => 'Arnaldo NOITE', 'turno' => 'NOITE'];
        $motoristas[]  = ['nome' => 'Jefinho MANHÃ', 'turno' => 'MANHÃ'];
        $motoristas[]  = ['nome' => 'Jefinho TARDE', 'turno' => 'TARDE'];
        $motoristas[]  = ['nome' => 'Jefinho Sábado MANHÃ', 'turno' => 'MANHÃ'];
        $motoristas[]  = ['nome' => 'Jefinho Sábado TARDE', 'turno' => 'TARDE'];
        $motoristas[]  = ['nome' => 'Boca MANHÃ', 'turno' => 'MANHÃ'];
        foreach ($motoristas as $motorista) {
            Motorista::create($motorista);
        }
        $clientes = [];
        $clientes[] = ['name' => 'Majijo', 'cnpj' => '14202745/0001-48',];
        $clientes[] = ['name' => 'Hospital Municipal', 'cnpj' => '61699567/0012-45',];
        $clientes[] = ['name' => 'Volks (Sapore)', 'cnpj' => '67945071/0001-38',];
        $clientes[] = ['name' => 'Rochinha ', 'cnpj' => '14583109/0001-03',];
        $clientes[] = ['name' => 'Ferrusmol', 'cnpj' => '59407148/0001-98',];
        $clientes[] = ['name' => 'Tecplas Jd América', 'cnpj' => '56840077/0001-24',];
        $clientes[] = ['name' => 'Lar do Idoso Miguel Arcanjo', 'cnpj' => '24959682/0001-22',];
        $clientes[] = ['name' => 'Alvotec', 'cnpj' => '36716037/0001-68',];
        $clientes[] = ['name' => 'Benacchio', 'cnpj' => '07599523/0001-45',];
        $clientes[] = ['name' => 'Swissbras ', 'cnpj' => '06222419/0001-74',];
        $clientes[] = ['name' => 'Brascam', 'cnpj' => '10426147/0001-00',];
        $clientes[] = ['name' => 'MCG-Farmaceutica', 'cnpj' => '18755529/0001-80',];
        $clientes[] = ['name' => 'MCG Perfumaria', 'cnpj' => '30874801/0001-47',];
        $clientes[] = ['name' => 'MCG Suplementos', 'cnpj' => '14600578/0001-93',];
        $clientes[] = ['name' => 'São Pedro', 'cnpj' => null,];
        $clientes[] = ['name' => 'Elian', 'cnpj' => null,];
        $clientes[] = ['name' => 'Josias', 'cnpj' => null,];
        $clientes[] = ['name' => 'Mercadinho Familia', 'cnpj' => null,];
        $clientes[] = ['name' => 'SK Automative', 'cnpj' => '08237002/0001-34',];
        $clientes[] = ['name' => 'Graúna', 'cnpj' => '03011370/0001-12',];
        $clientes[] = ['name' => 'Brasilit', 'cnpj' => '66140955/0001-52',];
        $clientes[] = ['name' => 'Ardagh (Sapore)', 'cnpj' => '67945071/0001-38',];
        $clientes[] = ['name' => 'Safran (Dom Rubi)', 'cnpj' => '16580640/0001-58',];
        $clientes[] = ['name' => 'JTU', 'cnpj' => null,];
        $clientes[] = ['name' => 'Tarkett (Evita Refeições)', 'cnpj' => '01208118/0003-34',];
        $clientes[] = ['name' => 'Ideal Refeições', 'cnpj' => '01227687/0001-65',];
        $clientes[] = ['name' => 'SEPAC (CSI) ', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'Prolind (Sr Mignon)', 'cnpj' => '36624431/0001-76',];
        $clientes[] = ['name' => 'Paraibuna', 'cnpj' => '46643474/0001-52',];
        $clientes[] = ['name' => 'Sesi Caçapava', 'cnpj' => '03779133/0127-06',];
        $clientes[] = ['name' => 'Sesi Taubate ', 'cnpj' => '03779133/0018-44',];
        $clientes[] = ['name' => 'Prefeitura da Estância de Tremembé', 'cnpj' => '46638714/0001-20',];
        $clientes[] = ['name' => 'Sesi Pinda', 'cnpj' => '03779133/0101-69',];
        $clientes[] = ['name' => 'Prefeitura de São Luiz do Paraitinga', 'cnpj' => '46631248/0001-51',];
        $clientes[] = ['name' => 'Fundhas ', 'cnpj' => '57522468/0001-63',];
        $clientes[] = ['name' => 'Hospital Pio 12 (Rede Madre)', 'cnpj' => '60194990/0006-82',];
        $clientes[] = ['name' => 'Recanto João de Deus (Rede Madre)', 'cnpj' => '60194990/0006-82',];
        $clientes[] = ['name' => 'Radici (Splendida)', 'cnpj' => '44342914/0001-06',];
        $clientes[] = ['name' => 'PMSJC PMSJC SSM Norte', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC PMSJC SSM Horto', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'UPA Alto da Ponte (Dom Rubi)', 'cnpj' => '16580640/0001-58',];
        $clientes[] = ['name' => 'Dom Rubi', 'cnpj' => '16580640/0001-58',];
        $clientes[] = ['name' => 'SOLUTECH (Dom Rubi)', 'cnpj' => '16580640/0001-58',];
        $clientes[] = ['name' => 'EMBRAER (Dom Rubi)', 'cnpj' => '16580640/0001-58',];
        $clientes[] = ['name' => 'Viação Jacareí', 'cnpj' => null,];
        $clientes[] = ['name' => 'Sr. Aparecido', 'cnpj' => null,];
        $clientes[] = ['name' => 'Hospital Vivalle (Sodexo)', 'cnpj' => '49930514/0001-35',];
        $clientes[] = ['name' => 'Belacci (Qualita)', 'cnpj' => '44342914/0001-06',];
        $clientes[] = ['name' => 'Latecoere (Evita Refeições)', 'cnpj' => '01208118/0003-34',];
        $clientes[] = ['name' => 'Inylbra (Lallegro)', 'cnpj' => '60785995/0001-75',];
        $clientes[] = ['name' => 'Saae Logística', 'cnpj' => '48962625/0012-13',];
        $clientes[] = ['name' => 'Saae Dutra - ETE', 'cnpj' => '48962625/0012-13',];
        $clientes[] = ['name' => 'Saae Centro - Sede', 'cnpj' => '48962625/0012-13',];
        $clientes[] = ['name' => 'Saae Liberdade - ECA', 'cnpj' => '48962625/0012-13',];
        $clientes[] = ['name' => 'Saae Bela Vista - ETA', 'cnpj' => '48962625/0012-13',];
        $clientes[] = ['name' => 'Manutenção Saude Jacareí', 'cnpj' => '46694139/0001-83',];
        $clientes[] = ['name' => 'CAPS III AD Jacareí', 'cnpj' => '46694139/0001-83',];
        $clientes[] = ['name' => 'CAPS I Infantil Jacareí', 'cnpj' => '46694139/0001-83',];
        $clientes[] = ['name' => 'CAPS II Jacareí', 'cnpj' => '46694139/0001-83',];
        $clientes[] = ['name' => 'SESI Jacarei', 'cnpj' => '03779133/0023-01',];
        $clientes[] = ['name' => 'Prefeitura de Jacarei', 'cnpj' => '46694139/0001-83',];
        $clientes[] = ['name' => 'Prefeitura de Igarata', 'cnpj' => '46694147/0001-20',];
        $clientes[] = ['name' => 'UPA Campo (Dom Rubi)', 'cnpj' => '16580640/0001-58',];
        $clientes[] = ['name' => 'Vale Cargas Armazém', 'cnpj' => '04879999/0001-41',];
        $clientes[] = ['name' => 'Vale Cargas Logística', 'cnpj' => '04879999/0001-41',];
        $clientes[] = ['name' => 'Breda Bruno', 'cnpj' => null,];
        $clientes[] = ['name' => 'Maringá (portaria Breda)', 'cnpj' => null,];
        $clientes[] = ['name' => 'Nutrimenta', 'cnpj' => '05671480/0001-36',];
        $clientes[] = ['name' => 'PMSJC Marronzinho', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'Saúde Mental (Ótima)', 'cnpj' => '48717946/0001-08',];
        $clientes[] = ['name' => 'Fragata', 'cnpj' => '41948086/0001-66',];
        $clientes[] = ['name' => 'Urbam Torrão (Bonizzoni)', 'cnpj' => '11893767/0001-03',];
        $clientes[] = ['name' => 'Serveng', 'cnpj' => '07444290/0001-01',];
        $clientes[] = ['name' => 'Avibras', 'cnpj' => '13668070/0005-98',];
        $clientes[] = ['name' => 'Fundação Casa', 'cnpj' => '20723388/0001-66',];
        $clientes[] = ['name' => 'UPA Putim (Dom Rubi)', 'cnpj' => '16580640/0001-58',];
        $clientes[] = ['name' => 'PMSJC SSM Sudeste', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'CJ Engenharia - Obra U ', 'cnpj' => '28425409/0001-79',];
        $clientes[] = ['name' => 'CJ Engenharia -Avante', 'cnpj' => '28425409/0001-79',];
        $clientes[] = ['name' => 'CJ Engenharia - São Judas', 'cnpj' => '28425409/0001-79',];
        $clientes[] = ['name' => 'PMSJC SSM Satélite', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC SSM Arborização', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'Casa 01 (Abrapi)', 'cnpj' => '35252296/0001-12',];
        $clientes[] = ['name' => 'Tecplas Eldorado', 'cnpj' => '56840077/0002-05',];
        $clientes[] = ['name' => 'HSMV', 'cnpj' => '54046081/0001-90',];
        $clientes[] = ['name' => 'Restaurante Brasil', 'cnpj' => null,];
        $clientes[] = ['name' => 'Wireflex (Restaurante Brasil)', 'cnpj' => null,];
        $clientes[] = ['name' => 'Marcos Dom Pedro', 'cnpj' => null,];
        $clientes[] = ['name' => 'PMSJC SSM Sul 2', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'Lumavale', 'cnpj' => '07146092/0001-61',];
        $clientes[] = ['name' => 'Utec', 'cnpj' => null,];
        $clientes[] = ['name' => 'Fátima', 'cnpj' => null,];
        $clientes[] = ['name' => 'Pardal', 'cnpj' => null,];
        $clientes[] = ['name' => 'CIETEL ', 'cnpj' => '27240030/0001-21',];
        $clientes[] = ['name' => 'Filó', 'cnpj' => null,];
        $clientes[] = ['name' => 'Tião', 'cnpj' => null,];
        $clientes[] = ['name' => 'Francisca Júlia ', 'cnpj' => '61956496/0002-47',];
        $clientes[] = ['name' => 'Divagner', 'cnpj' => null,];
        $clientes[] = ['name' => 'Antonio', 'cnpj' => null,];
        $clientes[] = ['name' => 'Sesi SJC', 'cnpj' => '03779133/0010-97',];
        $clientes[] = ['name' => 'PMSJC CAPS SUL', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'Viação Mimo Jacareí', 'cnpj' => '01247689/0005-39',];
        $clientes[] = ['name' => 'PSV', 'cnpj' => '03531880/0001-10',];
        $clientes[] = ['name' => 'Winoa IKK ( Qualitá)', 'cnpj' => '44342914/0001-06',];
        $clientes[] = ['name' => 'Adatex Matriz (Ideal)', 'cnpj' => '01227687/0001-65',];
        $clientes[] = ['name' => 'Adatex Filial (Ideal)', 'cnpj' => '01227687/0001-65',];
        $clientes[] = ['name' => 'Santa Casa Jacareí', 'cnpj' => '50471564/0001-80',];
        $clientes[] = ['name' => 'Hospital Antonio Afonso', 'cnpj' => '61879813/0001-98',];
        $clientes[] = ['name' => 'Policlin Jacarei ', 'cnpj' => '45184066/0004-60',];
        $clientes[] = ['name' => ' Hospital Alvorada ', 'cnpj' => '50482298/0001-91',];
        $clientes[] = ['name' => 'Conde Atacado', 'cnpj' => '71605265/0102-05',];
        $clientes[] = ['name' => 'Rosemary ', 'cnpj' => null,];
        $clientes[] = ['name' => 'Polipet Delivery - Carlos', 'cnpj' => '05886844/0003-67',];
        $clientes[] = ['name' => 'Polipet Delivery - Talitha', 'cnpj' => '05886844/0003-67',];
        $clientes[] = ['name' => 'Polipet Delivery - Beraldi', 'cnpj' => '05886844/0003-67',];
        $clientes[] = ['name' => 'Centro Médico Vivalle', 'cnpj' => '49930514/0001-35',];
        $clientes[] = ['name' => 'Sodimac (Bella Nutri)', 'cnpj' => '32136274/0001-07',];
        $clientes[] = ['name' => 'Sams Clube', 'cnpj' => '67945071/0001-38',];
        $clientes[] = ['name' => 'HortShop Jd Indústrias', 'cnpj' => '09535503/0001-36',];
        $clientes[] = ['name' => 'Mater Dei', 'cnpj' => '67945071/0001-38',];
        $clientes[] = ['name' => 'Colegio Poliedro ', 'cnpj' => '08204478/0001-45',];
        $clientes[] = ['name' => 'Esfera ', 'cnpj' => '67945071/0001-38',];
        $clientes[] = ['name' => 'Royal Care (Ótima)', 'cnpj' => '48717946/0001-08',];
        $clientes[] = ['name' => 'Polipet Vidoca', 'cnpj' => '05886844/0003-67',];
        $clientes[] = ['name' => 'CEC', 'cnpj' => '00464450/0001-35',];
        $clientes[] = ['name' => 'Polipet Parque', 'cnpj' => '05886844/0005-29',];
        $clientes[] = ['name' => 'Fundhas (ZONA NORTE)', 'cnpj' => '57522468/0001-63',];
        $clientes[] = ['name' => 'Asilo Tivoli ', 'cnpj' => '36226466/0001-57',];
        $clientes[] = ['name' => 'Hospital Antoninho (Rede Madre)', 'cnpj' => '60194990/0007-63',];
        $clientes[] = ['name' => 'PMSJC Guarda Pq Santos D.', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC SSM Pq. Santos D.', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'Curso Poliedro', 'cnpj' => '96488515/0001-51',];
        $clientes[] = ['name' => 'Abrigo AVD', 'cnpj' => '09123386/0001-01',];
        $clientes[] = ['name' => 'Hotel Lisboa ', 'cnpj' => '14503618/0001-89',];
        $clientes[] = ['name' => 'Santa Casa SJC ', 'cnpj' => '45186053/0001-87',];
        $clientes[] = ['name' => 'CRF Presidio Feminino', 'cnpj' => '96291141/0150-20',];
        $clientes[] = ['name' => 'Abrigo Feminino (Apar)', 'cnpj' => '01680455/0001-68',];
        $clientes[] = ['name' => 'Abrigo Masculino (Apar)', 'cnpj' => '01680455/0001-68',];
        $clientes[] = ['name' => 'Asilo Vilas Lobos', 'cnpj' => '36226466/0001-57',];
        $clientes[] = ['name' => 'PMSJC SSM Pq Cidade', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC Manutenção Saúde Pq da Cidade', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC Esportes Pq Cidade', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC Urbanismo Pq Cidade', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC Educação CEFE', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC Guarda CEFE EQP', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC Cidade da Educação 2', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC SSM Centro', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC SSM Sul 1', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC SSM Fac', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC SSM Portelinha', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC SSM Carpintaria', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC SSM Serralheria', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'Techal', 'cnpj' => '02662541/0001-00',];
        $clientes[] = ['name' => 'Orguel', 'cnpj' => '19537752/0020-87',];
        $clientes[] = ['name' => 'Poliedro CD', 'cnpj' => '05783379/0006-81',];
        $clientes[] = ['name' => 'Poliedro CD II Novo ', 'cnpj' => '18171089/0001-14',];
        $clientes[] = ['name' => 'Poliedro CEV', 'cnpj' => '05783379/0001-77',];
        $clientes[] = ['name' => 'Ferreira', 'cnpj' => '16422783/0001-31',];
        $clientes[] = ['name' => 'Kodak', 'cnpj' => '69032720/0001-35',];
        $clientes[] = ['name' => 'Calfer', 'cnpj' => '01305262/0001-27',];
        $clientes[] = ['name' => 'DIGEX ', 'cnpj' => '44342914/0001-06',];
        $clientes[] = ['name' => 'Cidade de Educação', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'Abordagem (Rodoviária)', 'cnpj' => '09123386/0001-01',];
        $clientes[] = ['name' => ' Cartão Para Todos', 'cnpj' => '12061049/0001-33',];
        $clientes[] = ['name' => 'Tiro de Guerra', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'Casa Brasil ', 'cnpj' => '61956496/0012-19',];
        $clientes[] = ['name' => 'Sr. Mignon', 'cnpj' => '36624431/0001-76',];
        $clientes[] = ['name' => 'Ótima', 'cnpj' => '48717946/0001-08',];
        $clientes[] = ['name' => 'Luzinete CAVAP', 'cnpj' => null,];
        $clientes[] = ['name' => 'UPA Novo Horizonte (Ótima)', 'cnpj' => '48717946/0001-08',];
        $clientes[] = ['name' => 'PMSJC SSM Leste 2', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'Premovale (Dom Rubi)', 'cnpj' => '16580640/0001-58',];
        $clientes[] = ['name' => 'Rosemberg (Ideal)', 'cnpj' => '01227687/0001-65',];
        $clientes[] = ['name' => 'Treves Caçapava (Sapore)', 'cnpj' => '67945071/0001-38',];
        $clientes[] = ['name' => 'CPW (Sapore)', 'cnpj' => '67945071/0001-38',];
        $clientes[] = ['name' => 'Merenda Caçapava', 'cnpj' => '45189305/0001-21',];
        $clientes[] = ['name' => 'FUSAM (Nutrindo)', 'cnpj' => '50453703/0001-43',];
        $clientes[] = ['name' => 'Yushiro (Ótima)', 'cnpj' => '48717946/0001-08',];
        $clientes[] = ['name' => 'UPA Eugênio de Melo (Ótima)', 'cnpj' => '48717946/0001-08',];
        $clientes[] = ['name' => 'PMSJC GUARDA Eugênio de Melo', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC SSM Eugênio de Melo', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'Viação Mimo Eug. Melo', 'cnpj' => '01274689/0005-39',];
        $clientes[] = ['name' => 'ELTEK - DELTA', 'cnpj' => '75584110/0082-02',];
        $clientes[] = ['name' => 'Denigris (Ideal)', 'cnpj' => '01227687/0001-65',];
        $clientes[] = ['name' => 'Serralharia Farmaconde', 'cnpj' => '10264525/0001-98',];
        $clientes[] = ['name' => 'Grafica Farmaconde', 'cnpj' => '27945133/0001-97',];
        $clientes[] = ['name' => 'BCA (Sr Mignon)', 'cnpj' => '36624431/0001-76',];
        $clientes[] = ['name' => 'Ultimate Eldorado', 'cnpj' => '06876242/0001-20',];
        $clientes[] = ['name' => 'LBV', 'cnpj' => '33915604/0527-70',];
        $clientes[] = ['name' => 'Paineiras', 'cnpj' => null,];
        $clientes[] = ['name' => 'Sueli', 'cnpj' => null,];
        $clientes[] = ['name' => 'Camara Municipal', 'cnpj' => '50448935/0001-03',];
        $clientes[] = ['name' => 'Polipet Vila Industrial', 'cnpj' => '05886844/0009-52',];
        $clientes[] = ['name' => 'TEGMA', 'cnpj' => '60691250/0001-47',];
        $clientes[] = ['name' => 'Abrigo Família', 'cnpj' => '09123386/0001-01',];
        $clientes[] = ['name' => 'PMSJC Defesa Civil', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC SSM Leste 1', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC SSM DMVE', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC SSM Elétrica', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC SSM Saneamento', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC SSM Hidráulica', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC SSM Antipichação', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC Fiscalização', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC Merenda Escolar ', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC GUARDA Ensino', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC GUARDA Sede Operacional', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC GUARDA ADM', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC GUARDA Anjos PMSJC GUARDA', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC GUARDA GAPE', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC GUARDA GTAM', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC GUARDA SEPAC', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC Almoxarifado Central', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC Manutenção da Saúde', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC Almoxarifado Saúde', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC Educação Vila Industrial', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC Cidade da Educação 1', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'Urbam Vila', 'cnpj' => '03345887/0001-48',];
        $clientes[] = ['name' => 'Joseense Ônibus', 'cnpj' => null,];
        $clientes[] = ['name' => 'PMSJC SSM Sede', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC SSM Concessionária', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC SSM Suprimentos', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC SSM Informática', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC SSM Gabinete', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'PMSJC SSM Poço Teotonio', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'Sanatório Maria Imaculada (Rede Madre)', 'cnpj' => '60194990/0002-59',];
        $clientes[] = ['name' => 'Clinica Amor e Saude', 'cnpj' => '39840362/0001-26',];
        $clientes[] = ['name' => 'Usimaza', 'cnpj' => '09111405/0001-71',];
        $clientes[] = ['name' => 'SENAI', 'cnpj' => null,];
        $clientes[] = ['name' => 'PMSJC Paço Municipal', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'Casa de Repouso', 'cnpj' => '31402148/0001-86',];
        $clientes[] = ['name' => 'PMSJC CAPS Centro Norte', 'cnpj' => '46643466/0001-06',];
        $clientes[] = ['name' => 'Imagem (Ideal)', 'cnpj' => '01227687/0001-65',];
        $clientes[] = ['name' => 'Padaria Nova Esperanca', 'cnpj' => null,];
        $clientes[] = ['name' => 'Santa Paula', 'cnpj' => '10977393844  (cpf)',];
        $clientes[] = ['name' => 'Retifica RM', 'cnpj' => null,];
        $clientes[] = ['name' => 'Graúna', 'cnpj' => '03011370/0001-12',];
        $clientes[] = ['name' => 'Contain', 'cnpj' => '31259349/0001-76',];
        $clientes[] = ['name' => 'Jairo', 'cnpj' => null,];
        $clientes[] = ['name' => 'Johnson (Sodexo)', 'cnpj' => '49930514/0001-35',];
        foreach ($clientes as $cliente) {
            Cliente::create($cliente);
        }
    }
}
