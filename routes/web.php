<?php

use App\Http\Controllers\MarcaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LancamentoController;
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
    
    Route::get('/produtosCategoriaEmEstoque/{categoria_id}', [HomeController::class, 'produtosCategoriaEmEstoque'])->name('produtosCategoria.index');

    Route::resource('categoria', CategoriaController::class);
    Route::resource('fornecedor', FornecedorController::class);
    Route::resource('marca', MarcaController::class);
    Route::resource('produto', ProdutoController::class);
    Route::resource('lote', LoteController::class);
    Route::resource('lancamento', LancamentoController::class);

    Route::group(['prefix' => 'ativar'],function(){
        Route::put('/categoria/{categoria_id}',[CategoriaController::class,'ativar'])->name('categoria.ativar');
        Route::put('/marca/{marca_id}',[MarcaController::class,'ativar'])->name('marca.ativar');
        Route::put('/produto/{produto_id}',[ProdutoController::class,'ativar'])->name('produto.ativar');
        Route::put('/fornecedor/{fornecedor_id}',[FornecedorController::class,'ativar'])->name('fornecedor.ativar');
        

    });
});
