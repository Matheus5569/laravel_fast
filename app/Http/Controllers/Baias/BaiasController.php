<?php

namespace App\Http\Controllers\Baias;

use App\Http\Controllers\Controller;
use App\Http\Requests\Baia\CriarBaiaRequest;
use App\Http\Requests\Baia\EditarBaiaRequest;
use App\Models\BaiaModel;
use Inertia\Inertia;

class BaiasController extends Controller
{
    public function index()
    {
        $baias = BaiaModel::query()
            ->where('removido', false)
            ->get();

        return Inertia::render('baias/Listar', [
            'baias' => $baias
        ]);
    }

    public function create(CriarBaiaRequest $request)
    {
        // dd($request->all());
        BaiaModel::query()->create([

            'nome' => $request->nome,

            'descricao' => $request->descricao,

            'status' => $request->status,

            'id_admin' => auth()->user()->id,

            'removido' => false,
        ]);
    }

    public function update(EditarBaiaRequest $request)
    {
        $baia = BaiaModel::query()
            ->where('id', $request->id)
            ->first();

        $baia->update([

            'nome' => $request->nome,

            'descricao' => $request->descricao,

            'status' => $request->status,
        ]);
    }

    public function remove($id)
    {
        $baia = BaiaModel::query()
            ->where('id', $id)
            ->first();

        $baia->update([
            'removido' => true
        ]);
    }
}