<?php

namespace App\Http\Controllers\Funcionarios;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class FuncionariosController extends Controller
{
    public function index()
    {
        return Inertia::render('funcionarios/Listar', [
            'funcionarios' => []
        ]);
    }

    public function persistir($id = null)
    {
        return Inertia::render('funcionarios/Persistir', [
            'funcionario' => null,
            'idFuncionario' => $id,
        ]);
    }
}