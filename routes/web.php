<?php

use App\Http\Controllers\MarcaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LancamentoController;
use App\Http\Controllers\MotoristaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Produto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/home');
}); 

Auth::routes();



Route::middleware('auth')->group(function () {

    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    Route::get('/clientsByName', [ClienteController::class, 'clientsByName'])->name('cliente.clientes');
    Route::get('/motoristaByName', [MotoristaController::class, 'motoristaByName'])->name('motorista.motorista');
    
    Route::post('/relatorioMotorista', [MotoristaController::class, 'relatorioMotorista'])->name('motorista.relatorio');
    Route::get('/motorista-relatorio', [MotoristaController::class, 'relatorioMotoristaIndex'])->name('motorista.relatorio.index');

    Route::post('/relatorioCliente', [ClienteController::class, 'relatorioCliente'])->name('cliente.relatorio');
    Route::get('/cliente-relatorio', [ClienteController::class, 'relatorioClienteIndex'])->name('cliente.relatorio.index');
    
    Route::post('/relatorioProduto', [ProdutoController::class, 'relatorioProduto'])->name('produto.relatorio');
    Route::get('/produto-relatorio', [ProdutoController::class, 'relatorioProdutoIndex'])->name('produto.relatorio.index');
    
    Route::get('/relatorioProducao', [ProdutoController::class, 'relatorioProducaoIndex'])->name('producao.relatorio');
    Route::post('/processRelatorioProducao', [ProdutoController::class, 'processRelatorioProducao'])->name('producao.relatorio.processar');
    
    Route::post('/pedidosDelete', [PedidoController::class, 'pedidosDelete'])->name('pedidos.multipleDelete');

    Route::get('/pedidoAtualiza', [PedidoController::class, 'baixaPedido'])->name('pedido.atualiza');
    Route::get('/getPedidoBaixa/{pedido_id}', [PedidoController::class, 'getPedidoBaixa'])->name('pedido.getBaixa');
    Route::post('/atualizaPedido/{pedido_id}', [PedidoController::class, 'atualizaPedido'])->name('pedido.troca');
    Route::get('/getPedidosByYear', [HomeController::class, 'getPedidosByYear']);
    
    Route::resource('categoria', CategoriaController::class);
    Route::resource('fornecedor', FornecedorController::class);
    Route::resource('marca', MarcaController::class);
    Route::resource('produto', ProdutoController::class);
    Route::resource('lote', LoteController::class);
    Route::resource('lancamento', LancamentoController::class);


    Route::resource('cliente', ClienteController::class);
    Route::resource('pedido', PedidoController::class);
    Route::resource('motorista', MotoristaController::class);

    Route::post('atualizar',[PedidoController::class,'atualizarPedidos'])->name('pedido.atualizar');

    Route::group(['prefix' => 'ativar'],function(){
        Route::put('/cliente/{categoria_id}',[ClienteController::class,'ativar'])->name('cliente.ativar');
        Route::put('/marca/{marca_id}',[MarcaController::class,'ativar'])->name('marca.ativar');
        Route::put('/produto/{produto_id}',[ProdutoController::class,'ativar'])->name('produto.ativar');
        Route::put('/fornecedor/{fornecedor_id}',[FornecedorController::class,'ativar'])->name('fornecedor.ativar');
        Route::put('/motorista/{motorista_id}',[MotoristaController::class,'ativar'])->name('motorista.ativar');
        

    });
});
