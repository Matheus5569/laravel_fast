<?php

namespace App\Http\Controllers\Produto;

use App\Http\Controllers\Controller;
use App\Http\Requests\Produto\CriarProdutoRequest;
use App\Http\Requests\Produto\EditarProdutoRequest;
use App\Models\ProdutoModel;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProdutoController extends Controller
{
    /**
     * Listar todos os produtos
     */
    public function produtos()
    {
        $produtos = ProdutoModel::query()
            ->where('removido', false)
            ->get();

        return Inertia::render('produto/Produtos', [
            'produtos' => $produtos,
        ]);
    }

    /**
     * Criar novo produto (via modal)
     */
    public function create(CriarProdutoRequest $request)
    {


        $produto = ProdutoModel::query()->create([
            'nome' => $request->nome,
            'preco' => $request->preco,
            'id_admin' => auth()->user()->id,
            'removido' => false,
        ]);

    }

    /**
     * Atualizar produto existente (via modal)
     */
    public function update(EditarProdutoRequest $request, )
    {

        $produto = ProdutoModel::query()->where('id', $request->id)->first();

    
        $produto->update([
            'nome' => $request->nome,
            'preco' => $request->preco,
        ]);
    }

    /**
     * Remover produto (soft delete)
     */
    public function remove($id_produto)
    {

        $produto = ProdutoModel::query()->where('id', $id_produto)->first();

        $produto->update([
            'removido' => true
        ]);
    }
}
