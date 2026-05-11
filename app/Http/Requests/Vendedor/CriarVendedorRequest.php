<?php

namespace App\Http\Requests\Vendedor;

use App\Http\Requests\Endereco\CriarEnderecoRequest;
use Illuminate\Foundation\Http\FormRequest;

class CriarVendedorRequest extends FormRequest
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
            'nome' => ['required', 'string'],
            'email' => ['required', 'email'],
            'cpf' => ['required', 'max:11'],
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
            'max' => ':attribute deve ser maximo: max caracteres',
            'numeric' => ':attribute deve ser um numero',
        ];
        $endereco = (new CriarEnderecoRequest())->messages();

        return array_merge($vendedor, $endereco);
    }
}
