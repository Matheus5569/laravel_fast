<?php

use App\Http\Controllers\Endereco\EnderecoController;
use App\Http\Controllers\Itens\ItensController;
use App\Http\Controllers\Vendedores\VendedoresController;
use App\Http\Controllers\Clientes\ClientesController;
use App\Http\Controllers\Vendas\VendasController;
use App\Http\Controllers\Produto\ProdutoController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
//lembra sempre de chamar o use toda vez que for chamar a uma nova classe e lembra de colocar o nome da pasta certa

// ===== LOGIN =====

Route::get('/', function () {
    return redirect('/login');
})->name('home');

// ===== DASHBOARD =====

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ===== VENDEDORES =====

Route::prefix('/vendedores')->middleware('role:admin')->group(function () {

    Route::get('/', [VendedoresController::class, 'index'])->name('vendedores.listar');
    Route::get('/persistir/{id?}', [VendedoresController::class, 'persistir'])->name('vendedores.persistir');

    Route::post('/create', [VendedoresController::class, 'create'])->name('vendedores.create');
    Route::put('/update/{id_vendedor}', [VendedoresController::class, 'update'])->name('vendedores.update');
    Route::delete('/remove/{id_vendedor}', [VendedoresController::class, 'remove'])->name('vendedores.remove');

});
// ===== CLIENTES =====

Route::prefix('/clientes')->middleware('role:admin')->group(function () {

    Route::get('/', [ClientesController::class, 'listar_clientes'])->name('clientes.listar');
    Route::get('/persistir/{id?}', [ClientesController::class, 'persistir_clientes'])->name('clientes.persistir');

    Route::post('/create', [ClientesController::class, 'create'])->name('clientes.create');
    Route::put('/update/{id_cliente}', [ClientesController::class, 'update'])->name('clientes.update');
    Route::delete('/remove/{id_cliente}', [ClientesController::class, 'remove'])->name('clientes.remove');

});

// ===== VENDAS =====

Route::prefix('/vendas')->group(function () {

    Route::get('/', [VendasController::class, 'vendas'])->name('vendas.listar');//listar
    Route::get('/vendas_persistir/{id?}', [VendasController::class, 'vendas_Persistir'])->name('vendas.persistir');

    Route::post('/create', [VendasController::class, 'create'])->name('vendas.create');
    Route::put('/update/{id_venda}', [VendasController::class, 'update'])->name('vendas.update');
    Route::delete('/remove/{id_venda}', [VendasController::class, 'remove'])->name('vendas.remove');
    Route::delete('/removerItem/{idItem}', [VendasController::class, 'removerItem'])->name('vendas.removerItem');
    Route::get('/excel/{idVenda}', [VendasController::class, 'export'])->name('vendas.export');
    Route::get('/pdf/{idVenda}', [VendasController::class, 'exportPdf'])->name('vendas.exportPdf');

});
// ===== PRODUTOS =====

Route::prefix('/produto')->middleware('role:admin')->group(function () {

    Route::get('/', [ProdutoController::class, 'produtos'])->name('produto.produtos');//listar

    Route::post('/create', [ProdutoController::class, 'create'])->name('produto.create');
    Route::put('/update/{id_produto}', [ProdutoController::class, 'update'])->name('produto.update');
    Route::delete('/remove/{id_produto}', [ProdutoController::class, 'remove'])->name('produto.remove');

});
// ===== ENDEREÇO =====

Route::prefix('/endereco')->middleware('role:admin')->group(function () {

    Route::post('/create', [EnderecoController::class, 'create'])->name('endereco.create');

});
// ===== ITENS =====

Route::prefix('/itens')->middleware('role:admin')->group(function () {


    Route::post('/create', [ItensController::class, 'create'])->name('itens.create');

});
//o uso do EX: name'produto.produtos' e usado para chamar o name no botão sem precisar chamar lá na pasta e usamos ele no deshboard


require __DIR__ . '/settings.php';
