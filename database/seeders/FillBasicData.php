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


        $categoriasIniciais = [
            [
                'nome' => 'Cereais, pães e tubérculos',
                'descricao' => 'Esta categoria inclui alimentos ricos em carboidratos complexos, como aveia, pão integral, arroz, batata doce, entre outros. São fontes essenciais de energia e fibras para o corpo humano.',
                'url_capa' => 'https://comerciagro.com.br/wp-content/uploads/2020/10/001-1.jpg',
                // 'url_capa' => 'https://fotos.web.sapo.io/i/Be211c9ae/20652162_VavRw.jpeg'

                
            ],
            [
                'nome' => 'Hortaliças',
                'descricao' => 'Grupo composto por verduras e legumes que são excelentes fontes de vitaminas, minerais e fibras essenciais para a saúde do organismo humano.',
                'url_capa' => 'https://horticeres.com.br/wp-content/uploads/2021/07/Como-fazer-o-cultivo-correto-de-hortalicas.jpg'
            ],
            [
                'nome' => 'Frutas',
                'descricao' => 'As frutas são fontes ricas em fibras, vitaminas e minerais essenciais para uma dieta equilibrada. Incluem variedades como maçãs, cerejas, morangos e muitas outras.',
                'url_capa' => 'https://ser.vitao.com.br/wp-content/uploads/2017/12/shutterstock_252338818-1-920x535.jpg'
            ],
            [
                'nome' => 'Leguminosas',
                'descricao' => 'Este grupo abrange grãos como feijões, lentilhas, grão-de-bico, soja e oleaginosas. São excelentes fontes de proteínas, fibras e outros nutrientes essenciais.',
                'url_capa' => 'https://content.paodeacucar.com/wp-content/uploads/2020/05/leguminosas-capa.jpg'
            ],
            [
                'nome' => 'Carnes e ovos',
                'descricao' => 'Principal grupo de alimentos fontes de proteínas de origem animal, fundamentais para o desenvolvimento e manutenção dos tecidos corporais.',
                'url_capa' => 'https://www.gov.br/saude/pt-br/assuntos/noticias/2022/outubro/carnes-peixes-e-ovos-sao-ricos-em-proteinas-de-alta-qualidade/19.jpg/@@images/71d640a8-59fa-4cd2-b86a-0e83c83ef4cd.jpeg'
            ],
            [
                'nome' => 'Leite e derivados',
                'descricao' => 'Inclui alimentos derivados do leite, como queijos e iogurtes, conhecidos por serem fontes ricas de cálcio, proteínas e outros nutrientes essenciais.',
                'url_capa' => 'https://s2-ge.glbimg.com/s8N_cVygvZYvbGN55vP6cub2K_4=/0x0:724x483/984x0/smart/filters:strip_icc()/i.s3.glbimg.com/v1/AUTH_bc8228b6673f488aa253bbcb03c80ec5/internal_photos/bs/2018/5/Y/2AZhoUSVOYCrMhyI5X9A/istock-910881428.jpg'
            ],
            [
                'nome' => 'Óleos e gorduras',
                'descricao' => 'Essa categoria abrange produtos utilizados na culinária para fritar, cozinhar e temperar alimentos. São fontes concentradas de energia essencial para o corpo humano.',
                'url_capa' => 'https://redeciadasaude.com.br/wp-content/uploads/2016/04/base-blog-site28.jpg'
            ],
            [
                'nome' => 'Açúcares',
                'descricao' => 'Os açúcares englobam diversos tipos de carboidratos presentes em alimentos, sendo uma fonte importante de energia. Podem ser encontrados em frutas, mel, açúcar de cana e outros alimentos processados.',
                'url_capa' => 'https://alimentesebem.sesisp.org.br/app/uploads/2021/06/Acucares-729x410.png'
            ],
            [
                'nome' => 'Processados',
                'descricao' => 'Alimentos processados são produtos que passaram por alterações em sua composição original para conservação, saborização ou aumento da vida útil. Podem incluir enlatados, congelados, embutidos, entre outros.',
                'url_capa' => 'https://i0.wp.com/naturvida.com.br/wp-content/uploads/2021/05/alimento-processado-1.jpg?resize=1000%2C667&ssl=1'
            ],
        ];
        foreach ($categoriasIniciais as $categoria) {
            Categoria::create($categoria);
        }

        for ($i = 0; $i < 30; $i++) {
            
            Fornecedor::create([
                "nome" => "Empresa " . chr(65 + $i), // Nomes de Empresa A a Empresa Z
                "cnpj" => $this->gerarCNPJValido(),
            ]);
        }


        $marcas_alimenticias = [
            ["nome" => "Nestlé"],
            ["nome" => "Coca-Cola"],
            ["nome" => "PepsiCo"],
            ["nome" => "Kellogg's"],
            ["nome" => "Mars, Incorporated"],
            ["nome" => "Mondelez International"],
            ["nome" => "General Mills"],
            ["nome" => "Danone"],
            ["nome" => "Unilever"],
            ["nome" => "Ferrero"],
        ];

        foreach ($marcas_alimenticias as $marca) {
            Marca::create($marca);
        }
        $produtos = [
            1 => 'Sucrilhos',
            2 => 'Couve',
            3 => 'Maçã',
            4 => 'Feijão',
            5 => 'Picanha',
            6 => 'Queijo',
            7 => 'Azeite',
            8 => 'Açucar mascavo',
            9 => 'Salsicha'
        ];

        $produtosCadastrados = [];
        foreach (range(1, 9) as $numero) {
            $informacaoNutricional = [
                "porcao" => $numero * 7,
                "proteina" => $numero * 7,
                "carboidrato" => $numero * 7,
                "gordura_total" => $numero * 7,
            ];
            $produto = [
                'nome' =>  $produtos[$numero],
                'descricao' => 'Descrição do '. $produtos[$numero],
                'unidade_medida' => 'kg',
                'fornecedor_id' => $numero,
                'marca_id' => $numero,
                'categoria_id' => $numero,

                'informacao_nutricional' => $informacaoNutricional,
                'created_by' => 1
            ];

            $produtosCadastrados[] = Produto::create($produto);
        }
        $lotes = [];
        foreach ($produtosCadastrados as $produto) {
            $lotes[] = Lote::create([
                'data_fabricacao' => Carbon::now()->subDays(3)->startOfDay(),
                'data_validade' => Carbon::now()->addDays(5)->startOfDay(),
                'preco_custo' => 10,
                'preco_venda' => 15,
                'produto_id' => $produto->id,
                'created_by' => 1
            ]);
        }
        foreach ($lotes as $lote) {
 
            $lancamento = Lancamento::create([
                'tipo' => TipoLancamento::Entrada,
                'lote_id' => $lote->id,
                'quantidade' => 130,
                'created_by' => 1

            ]);
        }
    }
    private function gerarCNPJValido()
    {
        $noveDigitos = '';
        for ($i = 0; $i < 9; $i++) {
            $noveDigitos .= mt_rand(0, 9);
        }

        $cnpj = $noveDigitos . '0001'; // Adiciona os quatro dígitos de ordem fixos

        $soma = 0;
        $multiplicador = 5;

        // Calcula o primeiro dígito verificador
        for ($i = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $multiplicador;
            $multiplicador = ($multiplicador === 2) ? 9 : ($multiplicador - 1);
        }

        $resto = $soma % 11;
        $dv1 = ($resto < 2) ? 0 : (11 - $resto);

        $cnpj .= $dv1;

        $soma = 0;
        $multiplicador = 6;

        // Calcula o segundo dígito verificador
        for ($i = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $multiplicador;
            $multiplicador = ($multiplicador === 2) ? 9 : ($multiplicador - 1);
        }

        $resto = $soma % 11;
        $dv2 = ($resto < 2) ? 0 : (11 - $resto);

        $cnpj .= $dv2;

        return $cnpj;
    }
}
