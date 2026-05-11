<?php

namespace App\Http\Controllers\Itens;

use App\Http\Controllers\Controller;
use App\Http\Requests\Itens\CriarItensRequest;
use App\Models\ItensModel;
use Illuminate\Http\Request;

class ItensController extends Controller
{
    public function create(CriarItensRequest $request)
    {
        ItensModel::create([
            'id_venda' => $request->id_venda, 
            'id_produto' => $request->id_produto, 
            'id_admin' => auth()->id(), 
        ]);
    }
}
