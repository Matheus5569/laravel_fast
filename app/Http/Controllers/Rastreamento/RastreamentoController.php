<?php

namespace App\Http\Controllers\Rastreamento;

use App\Http\Controllers\Controller;
use App\Models\VendaModel;
use Illuminate\Http\Request;
use App\Models\RastreamentoModel;

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
    public function avancarStatus($qrCode)
    {
        // procurar venda
        // descobrir próximo status
        // atualizar tb_venda
        // inserir em tb_rastreamento
    }

}
