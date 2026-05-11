<?php

namespace App\Http\Controllers\Endereco;

use App\Http\Controllers\Controller;
use App\Http\Requests\Endereco\CriarEnderecoRequest;
use App\Models\EnderecoModel;
use Illuminate\Http\Request;

class EnderecoController extends Controller
{
    public function create(CriarEnderecoRequest $request)
    {
        EnderecoModel::create([
            'cep' => $request->cep,
            'rua' => $request->rua,
            'numero' => $request->numero,
            'complemento' => $request->complemento,
            'bairro' => $request->bairro,
            'cidade' => $request->cidade,
            'estado' => $request->estado,
        ]);
    }
}
