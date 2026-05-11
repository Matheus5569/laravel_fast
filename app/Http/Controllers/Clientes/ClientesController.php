<?php

namespace App\Http\Controllers\Clientes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cliente\CriarClientesRequest;
use App\Http\Requests\Cliente\EditarClienteRequest;
use App\Models\ClienteModel;
use App\Models\EnderecoModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ClientesController extends Controller
{
    public function listar_clientes()
    {
        $clientes = ClienteModel::query()
            ->with('endereco')
            ->where('removido', false)
            ->get();

        return Inertia::render('clientes/Clientes', [
            'clientes' => $clientes,
        ]);
    }

    public function persistir_clientes($id = null)
    {

        $cliente = ClienteModel::query()
            ->with('endereco')
            ->where('id', $id)->first();
            

        if (!empty($id) && empty($cliente)) {
            return redirect()->route('clientes.listar');
        }

        return Inertia::render('clientes/Persistir_Clientes', [
            'idcliente' => $id,
            'cliente' => $cliente,
        ]);
    }

    public function create(CriarClientesRequest $request)
    {
        $conn = \DB::connection();
        try {
            $conn->beginTransaction();

            $endereco = EnderecoModel::query()->create([
                'cep' => $request->cep,
                'rua' => $request->rua,
                'numero' => $request->numero,
                'complemento' => $request->complemento,
                'bairro' => $request->bairro,
                'cidade' => $request->cidade,
                'estado' => $request->estado,
            ]);

            $cliente = ClienteModel::query()->create([
                'nome' => $request->nome,
                'email' => $request->email,
                'id_endereco' => $endereco->id,
                'id_admin' => auth()->user()->id,
            ]);

            $conn->commit();

            return redirect()->route('clientes.listar')
                ->with('success', 'Cliente criado com sucesso!');

        } catch (\Exception $e) {
            $conn->rollBack();
            return back()->with('error', 'Erro ao criar cliente: ' . $e->getMessage());
        }
    }

    public function update(EditarClienteRequest $request, $id)
    {
        $conn = \DB::connection();
        try {
            $conn->beginTransaction();

            $cliente = ClienteModel::with('endereco')
                ->where('id', $id)
                ->firstOrFail();

            $cliente->update([
                'nome' => $request->nome,
                'email' => $request->email,
            ]);

            if ($cliente->endereco) {
                $cliente->endereco->update([
                    'cep' => $request->cep,
                    'rua' => $request->rua,
                    'numero' => $request->numero,
                    'complemento' => $request->complemento,
                    'bairro' => $request->bairro,
                    'cidade' => $request->cidade,
                    'estado' => $request->estado,
                ]);
            }

            $conn->commit();

            return redirect()->route('clientes.listar')
                ->with('success', 'Cliente atualizado com sucesso!');

        } catch (Exception $e) {
            $conn->rollBack();
            return back()->with('error', 'Erro ao atualizar cliente: ' . $e->getMessage());
        }
    }

    public function remove($id)
    {
        try {
            $cliente = ClienteModel::where('id', $id)->firstOrFail();

            $cliente->update([
                'removido' => true,
            ]);

            return redirect()->route('clientes.listar')
                ->with('success', 'Cliente removido com sucesso!');

        } catch (Exception $e) {
            return back()->with('error', 'Erro ao remover cliente: ' . $e->getMessage());
        }
    }
}