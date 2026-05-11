<?php

namespace App\Http\Requests\Itens;

use Illuminate\Foundation\Http\FormRequest;

class CriarItensRequest extends FormRequest
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
            'id_venda' => ['required', 'integer', 'exists:tb_venda,id'],
            'id_produto' => ['required', 'integer', 'exists:tb_produto,id'],
            'valor' => ['required', 'numeric', 'min:0'],
            'quantidade' => ['required', 'integer', 'min:1'],
            'id_admin' => ['required', 'integer', 'exists:users,id'],
        ];
    }
    public function messages()
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'integer' => 'O campo :attribute deve ser um número inteiro.',
            'numeric' => 'O campo :attribute deve ser um número válido.',
            'min' => 'O campo :attribute deve ser no mínimo :min.',
            'exists' => 'O :attribute informado não existe.',
            'boolean' => 'O campo :attribute deve ser verdadeiro ou falso.',
        ];

    }
}
