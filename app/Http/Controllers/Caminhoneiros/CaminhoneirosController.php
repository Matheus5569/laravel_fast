<?php

namespace App\Http\Controllers\Caminhoneiros;

use App\Http\Controllers\Controller;
use App\Http\Requests\Caminhoneiro\CriarCaminhoneiroRequest;
use App\Http\Requests\Caminhoneiro\EditarCaminhoneiroRequest;
use App\Models\CaminhoneiroModel;
use Exception;
use Inertia\Inertia;

class CaminhoneirosController extends Controller
{
    public function index()
    {
        $caminhoneiros = CaminhoneiroModel::query()
            ->where('removido', false)
            ->get();

        return Inertia::render('caminhoneiros/Listar', [
            'caminhoneiros' => $caminhoneiros
        ]);
    }

    public function persistir($id = null)
    {
        $caminhoneiro = CaminhoneiroModel::query()
            ->where('id', $id)
            ->where('removido', false)
            ->first();

        if (!empty($id) && empty($caminhoneiro)) {
            return redirect()->route('caminhoneiros.listar');
        }

        return Inertia::render('caminhoneiros/Persistir', [

            'idcaminhoneiro' => $id,

            'caminhoneiro' => $caminhoneiro,
        ]);
    }

    public function create(CriarCaminhoneiroRequest $request)
    {
        try {

            CaminhoneiroModel::query()->create([

                'nome' => $request->nome,

                'cpf' => $request->cpf,

                'telefone' => $request->telefone,

                'placa_caminhao' => $request->placa_caminhao,

                'status' => $request->status,

                'id_admin' => auth()->user()->id,

                'removido' => false,
            ]);

            return redirect()
                ->route('caminhoneiros.listar')
                ->with('success', 'Caminhoneiro criado com sucesso!');

        } catch (Exception $e) {

            return back()
                ->with('error', 'Erro ao criar caminhoneiro: ' . $e->getMessage());
        }
    }

    public function update(
        EditarCaminhoneiroRequest $request,
        $id_caminhoneiro
    )
    {
        try {

            $caminhoneiro = CaminhoneiroModel::query()
                ->where('id', $id_caminhoneiro)
                ->firstOrFail();

            $caminhoneiro->update([

                'nome' => $request->nome,

                'cpf' => $request->cpf,

                'telefone' => $request->telefone,

                'placa_caminhao' => $request->placa_caminhao,

                'status' => $request->status,
            ]);

            return redirect()
                ->route('caminhoneiros.listar')
                ->with('success', 'Caminhoneiro atualizado com sucesso!');

        } catch (Exception $e) {

            return back()
                ->with('error', 'Erro ao atualizar caminhoneiro: ' . $e->getMessage());
        }
    }

    public function remove($id_caminhoneiro)
    {
        try {

            $caminhoneiro = CaminhoneiroModel::query()
                ->where('id', $id_caminhoneiro)
                ->firstOrFail();

            $caminhoneiro->update([
                'removido' => true
            ]);

            return redirect()
                ->route('caminhoneiros.listar')
                ->with('success', 'Caminhoneiro removido com sucesso!');

        } catch (Exception $e) {

            return back()
                ->with('error', 'Erro ao remover caminhoneiro: ' . $e->getMessage());
        }
    }
}