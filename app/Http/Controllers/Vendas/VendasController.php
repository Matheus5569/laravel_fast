<?php

namespace App\Http\Controllers\Vendas;

use App\Exports\VendaExport;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Venda\CriarVendaRequest;
use App\Http\Requests\Venda\EditarVendaRequest;
use App\Models\ClienteModel;
use App\Models\ItemModel;
use App\Models\ItensModel;
use App\Models\ProdutoModel;
use App\Models\VendaModel;
use App\Models\VendedorModel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Laravel\Pail\ValueObjects\Origin\Console;
use Dompdf\Dompdf;
use Illuminate\Support\Str;
use App\Models\RastreamentoModel;

class VendasController extends Controller
{
    /**
     * TELA DE LISTAGEM DE VENDAS
     * Mostra todas as vendas com filtros de vendedor e cliente
     */
    public function vendas()
    {
        // Busca todos os vendedores ativos e carrega os dados do usuário relacionado
        $vendedores = VendedorModel::query()->with('users')->where('removido', false)->get();

        $clientes = ClienteModel::query()->where('removido', false)->get();


        $vendas = VendaModel::query()->with(['cliente', 'vendedor.users', 'itens.produto'])->where('removido', false)->
            when(auth()->user()->parent_user_id, function ($query) {
                $query->where('id_vendedor', auth()->user()->id);
            })->get();

        return Inertia::render(
            'vendas/Venda',
            [
                'vendas' => $vendas,
                'vendedores' => $vendedores,
                'clientes' => $clientes,
            ]
        );
    }

    /**
     * TELA DE CRIAR/EDITAR VENDA
     * Se tiver ID, carrega os dados da venda para edição
     * Se não tiver ID, abre tela em branco para criar nova venda
     */
    public function vendas_Persistir($idVenda = null)
    {
        // Carrega produtos e clientes para os selects do formulário
        $produtos = ProdutoModel::query()->where('removido', false)->get();
        $clientes = ClienteModel::query()->where('removido', false)->get();

        // Se tiver ID, busca a venda com seus itens e produtos para editar
        $venda = VendaModel::query()->with(['itens.produto'])->where('id', $idVenda)->first();

        return Inertia::render(
            'vendas/Vendas_Persistir',
            [
                'clientes' => $clientes,
                'produtos' => $produtos,
                'venda' => $venda ?? null, // Se não tiver venda, manda null (criação)
            ]
        );
    }

    /**
     * SALVAR NOVA VENDA (POST)
     * Cria uma nova venda e seus itens no banco de dados
     */
    public function create(CriarVendaRequest $request)
    {
        $conn = \DB::connection();

        try {
            // Inicia a transação - tudo ou nada
            $conn->beginTransaction();

            // Pega o ID do admin (usuário logado ou o admin dele)
            $idAdmin = auth()->user()->parent_user_id ?? auth()->user()->id;

            // 1. CRIA A VENDA
            $venda = VendaModel::query()->create([
                'data' => $request->data_venda,        // Data da venda
                'id_cliente' => $request->id_cliente,  // Cliente que comprou
                'id_vendedor' => auth()->user()->id,   // Vendedor que fez a venda
                'id_admin' => $idAdmin,                 // Admin que cadastrou

                'qr_code' => Str::uuid(),
                'status_atual' => 'orcamento_criado',
                'baia' => $request->baia,
                'caminhoneiro' => $request->caminhoneiro,
                'destino' => $request->destino,
            ]);

            RastreamentoModel::query()->create([
                'id_venda' => $venda->id,
                'status' => 'orcamento_criado',
                'observacao' => 'Orçamento criado',
            ]);
            // 2. CRIA OS ITENS DA VENDA (um por um)
            foreach ($request->itens as $item) {
                ItensModel::query()->create([
                    'id_venda' => $venda->id,           // Liga o item à venda criada
                    'id_produto' => $item['id_produto'],
                    'quantidade' => $item['quantidade'],
                    'valor' => $item['valor'],
                    'id_admin' => $idAdmin,
                ]);
            }


            // Se chegou até aqui, tudo deu certo - confirma a transação
            $conn->commit();

            // Volta para a listagem de vendas
            return redirect()->route('vendas.listar');

        } catch (\Exception $e) {
            // Se deu erro, desfaz tudo que foi feito
            $conn->rollBack();
            dd($e); // Mostra o erro (depois trocar por algo mais amigável)
        }
    }

    /**
     * ATUALIZAR VENDA EXISTENTE (PUT)
     * Edita uma venda e seus itens
     */
    public function update(EditarVendaRequest $request, mixed $idVenda)
    {
        $conn = \DB::connection();
        try {
            $conn->beginTransaction();

            // Busca a venda que vai ser editada
            $venda = VendaModel::query()->where('id', $idVenda)->first();

            // Atualiza os dados principais da venda
            $venda->update([
                'data' => $request->data_venda,
                'id_cliente' => $request->id_cliente,
                'baia' => $request->baia,
                'caminhoneiro' => $request->caminhoneiro,
                'destino' => $request->destino,
            ]);

            // Atualiza ou cria os itens
            foreach ($request->itens as $item) {
                if (!empty($item['id'])) {
                    // Se o item já tem ID, é porque já existe no banco → ATUALIZA
                    $itemVez = ItensModel::query()->where('id', $item['id'])->first();
                    $itemVez->update([
                        'id_produto' => $item['id_produto'],
                        'valor' => $item['valor'],
                    ]);
                } else {
                    // Se não tem ID, é um item novo → CRIA
                    ItensModel::query()->create([
                        'id_venda' => $idVenda,
                        'id_produto' => $item['id_produto'],
                        'quantidade' => $item['quantidade'],
                        'valor' => $item['valor'],
                        'id_admin' => auth()->user()->parent_user_id ?? auth()->user()->id,
                    ]);
                }
            }

            $conn->commit();
            return redirect()->route('vendas.listar');

        } catch (\Exception $e) {
            $conn->rollBack();
            dd($e);
        }
    }

    /**
     * REMOVER VENDA (SOFT DELETE)
     * Não deleta de verdade, só marca como removido = true
     */
    public function remove($idVenda)
    {
        $venda = VendaModel::query()->where('id', $idVenda)->first();
        $venda->update([
            'removido' => true, // Marca como removido
        ]);
        return redirect()->route('vendas.listar');
    }

    /**
     * REMOVER ITEM ESPECÍFICO DE UMA VENDA
     * Usado quando o usuário quer deletar um item durante a edição
     */
    public function removerItem($idItem)
    {
        $item = ItensModel::query()->where('id', $idItem)->first();
        $idVenda = $item->id_venda; // Guarda o ID da venda pra redirecionar de volta
        $item->delete(); // Deleta o item (aqui é delete mesmo, não soft delete)

        // Volta para a tela de edição da venda
        return redirect()->route('vendas.persistir', $idVenda);
    }
    public function export($idVenda)
    {
        try {
            return Excel::download(new VendaExport($idVenda), 'venda.xlsx');
        } catch (\Exception $e) {
            dd($e);
        }
    }
    public function exportPdf($idItem)
    {
        $venda = VendaModel::with(['itens.produto', 'cliente', 'vendedor'])->find($idItem);

        $dompdf = new Dompdf();
        $dompdf->LoadHtml(view('venda', [
            'venda' => $venda,
        ]));

        $dompdf->setPaper('A4', 'landscape');

        $dompdf->render();

        return $dompdf->stream();
    }
}