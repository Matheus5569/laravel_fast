<?php

namespace App\Http\Controllers\Vendedores;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendedor\CriarVendedorRequest;
use App\Http\Requests\Vendedor\EditarVendedorRequest;
use App\Models\EnderecoModel;
use App\Models\User;
use App\Models\VendedorModel;
use Dba\Connection;
use Exception;
use Illuminate\Http\Request;
use inertia\inertia;
use function Laravel\Prompts\password;

class VendedoresController extends Controller
{


    public function index()
    {

        $vendedores = VendedorModel::query()->with('users')->where('removido', false)->get();


        return inertia::render('vendedores/Listar', [
            'vendedores' => $vendedores,
        ]);
    }
    public function persistir($idvendedor = null)
    {

        if ($idvendedor !== null) {
            $vendedores = VendedorModel::with(['users', 'endereco'])->where('id_vendedor', $idvendedor)->first();
        }
        return inertia::render(
            'vendedores/Persistir',
            [
                'vendedor' => $vendedores ?? null,
                'idVendedor' => $idvendedor,
            ]
        );
    }
    public function create(CriarVendedorRequest $request)
    {
        //dd = daydump mostra os campos que recebemos 
        $conn = \DB::connection();
        try {
            $conn->beginTransaction();
            $emailAntesArroba = explode('@', $request->email)[0];

            $user = User::create([
                'name' => $request->nome,
                'parent_user_id' => auth()->user()->id,
                'email' => $request->email,
                'password' => password_hash($emailAntesArroba, PASSWORD_DEFAULT),
            ]);

            $user->addRole('vendedor');
            $endereco = EnderecoModel::query()->create([
                'cep' => $request->cep,
                'rua' => $request->rua,
                'numero' => $request->numero,
                'complemento' => $request->complemento,
                'bairro' => $request->bairro,
                'cidade' => $request->cidade,
                'estado' => $request->estado,
            ]);
            $vendedor = VendedorModel::query()->create([
                'id_vendedor' => $user->id,
                'comissao' => $request->comissao,
                'cpf' => $request->cpf,
                'observacoes' => $request->observacoes,
                'id_endereco' => $endereco->id,
                'id_admin' => auth()->user()->id,

            ]);
            $conn->commit();

            return redirect()->route('vendedores.listar')
                ->with('success', 'Vendedor criado com sucesso!');
        } catch (Exception $e) {
            $conn->rollBack();
            dd($e);
        }
    }
    public function update(EditarVendedorRequest $request, $idVendedor)
    {
        //dd = daydump mostra os campos que recebemos 
        $conn = \DB::connection();
        try {
            $conn->beginTransaction();
            $vendedor = VendedorModel::query()->where('id_vendedor', $idVendedor)->first();
            $vendedor->users->update([
                'name' => $request->nome,
                'email' => $request->email,
            ]);

            $vendedor->endereco->update([
                'cep' => $request->cep,
                'rua' => $request->rua,
                'numero' => $request->numero,
                'complemento' => $request->complemento,
                'bairro' => $request->bairro,
                'cidade' => $request->cidade,
                'estado' => $request->estado,
            ]);

            $vendedor->update([
                'comissao' => $request->comissao,
                'cpf' => $request->cpf,
                'observacoes' => $request->observacoes,
            ]);
            $conn->commit();
            return redirect()->route('vendedores.listar')
                ->with('success', 'Vendedor atualizado com sucesso!');
        } catch (Exception $e) {
            $conn->rollBack();
            return redirect()->back()->with('error', 'Erro ao atualizar vendedor: ' . $e->getMessage());
            dd($e);
        }
    }

    public function remove($idVendedor)
    {
        $vendedor = VendedorModel::query()->where('id_vendedor', $idVendedor)->first();

        $vendedor->update([
            'removido' => true,
        ]);

        return redirect()->route('vendedores.listar')->with('succes', 'Vendedor removido com sucesso');
    }

}
