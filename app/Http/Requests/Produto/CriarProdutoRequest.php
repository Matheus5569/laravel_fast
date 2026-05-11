<?php

namespace App\Http\Requests\Produto;

use Illuminate\Foundation\Http\FormRequest;

class CriarProdutoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'preco' => ['required', 'numeric', 'min:0']

        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute e obrigatorio',
            'string' => ':attribute deve ser uma string',
            'email' => ':attribute deve ser um email'   ,
            'max' => ':attribute deve ser maximo: max caracteres',
            'numeric' => ':attribute deve ser um numero',
            'array' => ':attribute deve ser um array',


        ];
    }
}
