<?php

namespace App\Http\Requests\Cliente;

use App\Http\Requests\Endereco\CriarEnderecoRequest;
use Illuminate\Foundation\Http\FormRequest;

class EditarClienteRequest extends FormRequest
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
        $cliente = [
            'nome' => ['required', 'string'],
            'email' => ['required', 'email'],
            'id_cliente' => ['required', 'integer', 'exists:tb_cliente,id'],
        ];
        $endereco = (new CriarEnderecoRequest())->rules();

        return array_merge($cliente, $endereco);
    }
    public function messages()
    {
        $cliente = [
            'required' => ':attribute e obrigatorio',
            'string' => ':attribute deve ser uma string',
            'email' => ':attribute deve ser um email',
            'min' => ':attribute deve ter no mínimo :min',
            'max' => ':attribute deve ser maximo: max caracteres',
            'numeric' => ':attribute deve ser um numero',
            'integer' => ':attribute deve ser uma número inteiro',
        ];
        $endereco = (new CriarEnderecoRequest())->messages();

        return array_merge($cliente, $endereco);
    }
}
