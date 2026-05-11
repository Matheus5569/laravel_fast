<?php

namespace App\Http\Requests\Vendedor;

use App\Http\Requests\Endereco\CriarEnderecoRequest;
use Illuminate\Foundation\Http\FormRequest;

class EditarVendedorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'cpf' => preg_replace('/\D/', '', $this->cpf),
            'cep' => preg_replace('/\D/', '', $this->cep),

        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $vendedor = [
            'id_vendedor' => ['required', 'integer', ' exists:tb_vendedor,id_vendedor'],
            'nome' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email,' . $this->id_vendedor . ',id'],
            'cpf' => ['required', 'max:11', 'min:11'],
            'observacoes' => ['nullable', 'string'],
            'comissao' => ['required', 'numeric'],


        ];
        $endereco = (new CriarEnderecoRequest())->rules();

        return array_merge($vendedor, $endereco);

    }

    public function messages()
    {
        $vendedor = [
            'required' => ':attribute e obrigatorio',
            'string' => ':attribute deve ser uma string',
            'email' => ':attribute deve ser um email',
            'min' => ':attribute deve ter no mínimo :min',
            'max' => ':attribute deve ser maximo: max caracteres',
            'numeric' => ':attribute deve ser um numero',
            'integer' => ':attribute deve ser uma número inteiro',
        ];
        $endereco = (new CriarEnderecoRequest())->messages();

        return array_merge($vendedor, $endereco);
    }
}

