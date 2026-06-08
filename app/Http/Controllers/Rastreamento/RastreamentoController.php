<?php

namespace App\Http\Controllers\Rastreamento;

use App\Http\Controllers\Controller;
use App\Models\VendaModel;
use Illuminate\Http\Request;
use App\Models\RastreamentoModel;
use Inertia\Inertia;

class RastreamentoController extends Controller
{
    public function visualizar($qrCode)
    {
        $venda = VendaModel::with([ 
            'cliente',
            'rastreamento'
        ])
            ->where('qr_code', $qrCode)
            ->firstOrFail();

        return $venda;
    }
    public function avancarStatus($idVenda)
    {
        $venda = VendaModel::findOrFail($idVenda);

        $status = [
            'orcamento_criado',
            'fazendo_materiais',
            'recorte',
            'pintura',
            'estufa_polimerizacao',
            'separacao',
            'aguardando_caminhoneiro',
            'concluido'
        ];

        $indiceAtual = array_search(
            $venda->status_atual,
            $status
        );

        if ($indiceAtual === false) {
            return back();
        }

        if ($indiceAtual < count($status) - 1) {

            $novoStatus = $status[$indiceAtual + 1];

            $venda->update([
                'status_atual' => $novoStatus
            ]);

            RastreamentoModel::create([
                'id_venda' => $venda->id,
                'status' => $novoStatus,
                'observacao' => 'Status atualizado'
            ]);
        }

        return redirect()->route('rastreamento.listar')
            ->with('success', 'Status atualizado com sucesso!');
    }
    public function index()
    {
        $vendas = VendaModel::with([
            'cliente',
            'rastreamento'
        ])
            ->where('removido', false)
            ->get();

        return Inertia::render(
            'rastreamento/Rastreamento',
            [
                'vendas' => $vendas
            ]
        );
    }

}
