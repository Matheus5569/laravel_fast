<?php

namespace App\Http\Requests\Cliente;

use App\Http\Requests\Endereco\CriarEnderecoRequest;
use GuzzleHttp\Psr7\Message;
use Illuminate\Foundation\Http\FormRequest;

class CriarClientesRequest extends FormRequest
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
            'max' => ':attribute deve ser maximo: max caracteres',
            'numeric' => ':attribute deve ser um numero',
            'array' => ':attribute deve ser um array',

        ];

        $endereco = (new CriarEnderecoRequest())->messages();

        return array_merge($cliente, $endereco);
    }
}
