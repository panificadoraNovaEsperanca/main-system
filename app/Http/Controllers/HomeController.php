<?php

namespace App\Http\Controllers;

use App\DataTables\AgendamentosDataTable;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Insumo;
use App\Models\Motorista;
use App\Models\Pedido;
use App\Models\PedidoProduto;
use App\Models\Produto;
use App\Repositories\CategoriaRepository;
use App\Repositories\ProdutoRepository;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View|RedirectResponse
    {
        $user = Auth::user();
        if ($user->grupoPermissao != null) {
            $user_group = $user->grupoPermissao->slug;


            if ($user_group == 'motorista') {

                return redirect('/motorista-entrega');
            } else if ($user_group == 'producao') {
                return redirect('/producaoBaixa');
            }
            else if ($user_group == 'almoxarifado') {
                return redirect('/estoque');
            }
        }
        try {
            $inicio = Carbon::now()->startOfDay();
            $fim = Carbon::now()->endOfDay();
            $primeiroDiaDoMes = Carbon::now()->startOfMonth();
            $ultimoDiaDoMes = Carbon::now()->endOfMonth();

            // CORREÇÃO: Garantir mesma consulta para card e gráfico
            $primeiroDiaDoMesStr = $primeiroDiaDoMes->toDateString();
            $ultimoDiaDoMesStr = $ultimoDiaDoMes->toDateString();

            // Dados para o gráfico anual - PRIMEIRO
            $year = Carbon::now()->year;
            $firstDateOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
            $lastDateOfYear = Carbon::createFromDate($year, 12, 31)->endOfDay();

            $meses = collect(range(1, 12))->map(function ($mes) {
                return ['mes' => $mes, 'quantidade' => 0];
            });

            $pedidos = Pedido::whereBetween('dt_previsao', [$firstDateOfYear, $lastDateOfYear])
                ->select(DB::raw('EXTRACT(MONTH FROM dt_previsao) as mes'), DB::raw('COUNT(*) as quantidade'))
                ->groupBy(DB::raw('EXTRACT(MONTH FROM dt_previsao)'))
                ->get();

            $quantidadePedidosPorMes = $meses->map(function ($item) use ($pedidos) {
                $quantidade = $pedidos->filter(function ($element) use ($item) {
                    return $element->mes == $item['mes'];
                });
                $quantidade = $quantidade->isNotEmpty() ? $quantidade->first()->quantidade : 0;

                return [
                    'mes' => $item['mes'],
                    'quantidade' => $quantidade
                ];
            });

            // CORREÇÃO PRINCIPAL: Usar o mesmo número do gráfico para o card
            $mesAtual = Carbon::now()->month;
            $totalPedidosMes = $quantidadePedidosPorMes->where('mes', $mesAtual)->first()['quantidade'] ?? 0;

            // Outros cards
            $pedidosHoje = Pedido::whereBetween('dt_previsao', [$inicio, $fim])->count();
            $totalClientes = Cliente::whereNull('deleted_at')->count();
            $totalProdutos = Produto::whereNull('deleted_at')->count();

            $valorEstimadoMes = PedidoProduto::join('pedidos', 'pedido_produtos.pedido_id', '=', 'pedidos.id')
                ->whereBetween('pedidos.dt_previsao', [$primeiroDiaDoMesStr, $ultimoDiaDoMesStr])
                ->sum(DB::raw('pedido_produtos.quantidade * pedido_produtos.preco'));

            $totalMotoristas = Motorista::whereNull('deleted_at')->count();

            // NOVOS: Dados para gráficos de insumos
            // Top 5 Insumos no Limite (próximos do estoque mínimo)
            $topInsumosNoLimite = Insumo::whereNull('deleted_at')
                ->where('quantidade_minima', '>', 0) // Só insumos com estoque mínimo definido
                ->whereRaw('quantidade_atual <= quantidade_minima * 1.2') // Está próximo ou abaixo do mínimo (até 20% acima)
                ->select('nome', 'quantidade_atual', 'quantidade_minima', 'unidade_medida')
                ->orderByRaw('(quantidade_atual / quantidade_minima) ASC') // Ordenar pelos mais críticos
                ->limit(5)
                ->get();

            // Evolução Total do Estoque (valor agregado de todos os insumos)
            $totalEstoqueAtual = Insumo::whereNull('deleted_at')->sum('quantidade_atual');
            $totalEstoqueMinimo = Insumo::whereNull('deleted_at')->sum('quantidade_minima');

            // Para uma evolução temporal, vamos pegar os últimos 30 dias de movimentações (se tiver tabela de movimentações)
            // Como não temos, vamos simular com dados mensais do ano
            $evolucaoEstoque = collect();
            for ($i = 11; $i >= 0; $i--) {
                $mes = Carbon::now()->subMonths($i);
                $evolucaoEstoque->push([
                    'mes' => $mes->format('M/Y'),
                    'total_estoque' => $totalEstoqueAtual - ($i * 100), // Simulação - substituir por dados reais se tiver
                ]);
            }

            // Preparar labels
            $numeroParaMes = [
                1 => 'Janeiro',
                2 => 'Fevereiro',
                3 => 'Março',
                4 => 'Abril',
                5 => 'Maio',
                6 => 'Junho',
                7 => 'Julho',
                8 => 'Agosto',
                9 => 'Setembro',
                10 => 'Outubro',
                11 => 'Novembro',
                12 => 'Dezembro'
            ];

            $labelsAnual = $quantidadePedidosPorMes->pluck('mes')->map(function ($mes) use ($numeroParaMes) {
                return $numeroParaMes[$mes];
            });

            $dataAnual = $quantidadePedidosPorMes->pluck('quantidade');

            // Outros dados para gráficos
            $seteDiasAtras = Carbon::now()->subDays(6)->startOfDay();
            $pedidosPorDia = Pedido::whereBetween('dt_previsao', [$seteDiasAtras, $fim])
                ->selectRaw('DATE(dt_previsao) as data, COUNT(*) as total')
                ->groupBy('data')
                ->orderBy('data')
                ->get();

            $topClientes = Pedido::whereBetween('dt_previsao', [$primeiroDiaDoMesStr, $ultimoDiaDoMesStr])
                ->join('clientes', 'pedidos.cliente_id', '=', 'clientes.id')
                ->selectRaw('clientes.name, COUNT(pedidos.id) as total_pedidos')
                ->groupBy('clientes.id', 'clientes.name')
                ->orderByDesc('total_pedidos')
                ->limit(5)
                ->get();

            $produtosMaisVendidos = PedidoProduto::join('pedidos', 'pedido_produtos.pedido_id', '=', 'pedidos.id')
                ->whereBetween('pedidos.dt_previsao', [$primeiroDiaDoMesStr, $ultimoDiaDoMesStr])
                ->join('produtos', 'pedido_produtos.produto_id', '=', 'produtos.id')
                ->selectRaw('produtos.nome, SUM(pedido_produtos.quantidade) as total_vendido')
                ->groupBy('produtos.id', 'produtos.nome')
                ->orderByDesc('total_vendido')
                ->limit(8)
                ->get();

            return view('home', compact(
                // Cards
                'totalPedidosMes',
                'pedidosHoje',
                'totalClientes',
                'totalProdutos',
                'valorEstimadoMes',
                'totalMotoristas',

                // Dados para gráficos
                'quantidadePedidosPorMes',
                'labelsAnual',
                'dataAnual',
                'pedidosPorDia',
                'topClientes',
                'produtosMaisVendidos',

                // NOVOS: Dados para gráficos de insumos
                'topInsumosNoLimite',
                'totalEstoqueAtual',
                'totalEstoqueMinimo',
                'evolucaoEstoque'
            ));
        } catch (\Exception $e) {
            dd($e);
            return back()->with('messages', ['error' => ['Não foi possível acessar a página principal!']]);
        }
    }

    public function getPedidosByYear()
    {

        $year = Carbon::now()->year;

        $firstDateOfYear = Carbon::createFromDate($year, 1, 1)->toDateString();
        $lastDateOfYear = Carbon::createFromDate($year, 12, 31)->toDateString();
        $meses = collect(range(1, 12))->map(function ($mes) {
            return ['mes' => $mes, 'quantidade' => 0];
        });
        $pedidos = Pedido::whereBetween('dt_previsao', [$firstDateOfYear, $lastDateOfYear])
            ->select(DB::raw('EXTRACT(MONTH FROM dt_previsao) as mes'), DB::raw('COUNT(*) as quantidade'))
            ->groupBy(DB::raw('EXTRACT(MONTH FROM dt_previsao)'))
            ->get();
        $quantidadePedidosPorMes = $meses->map(function ($item) use ($pedidos) {
            $quantidade = $pedidos->filter(function ($element) use ($item) {
                return $element->mes == $item['mes'];
            });
            $quantidade = $quantidade->isNotEmpty() ?  $quantidade->first()->quantidade : 0;

            return [
                'mes' => $item['mes'],
                'quantidade' => $quantidade
            ];
        });


        return response()->json($quantidadePedidosPorMes);
    }

    public function produtosCategoriaEmEstoque(int $categoria_id): View|RedirectResponse
    {

        try {
            $produtoRepository = new ProdutoRepository(new Produto());
            $produtosCategoria = $produtoRepository->getIndexHome($categoria_id);
            $categoria = Categoria::findOrFail($categoria_id);
            return view('produtosCategoria.index', compact('produtosCategoria', 'categoria'));
        } catch (\Exception $e) {
            return back()->with('messages', ['error' => ['Não foi possível encontrar a categoria!']]);
        }
    }
}
