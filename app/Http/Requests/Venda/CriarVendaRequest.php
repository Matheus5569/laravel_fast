<?php

namespace App\Http\Requests\Venda;

use Illuminate\Foundation\Http\FormRequest;

class CriarVendaRequest extends FormRequest
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
            'data_venda' => ['required', 'date'],
            'id_cliente' => ['required', 'exists:tb_cliente,id'],
            'itens' => ['required', 'array', 'min:1'],
            'itens.*.id_produto' => ['required', 'exists:tb_produto,id'],
            'itens.*.quantidade' => ['required', 'integer', 'min:1'], 
            'itens.*.valor' => ['required', 'numeric', 'min:0'],
        ];
    }
    public function messages(): array
    {
        return [
            'required' => ':attribute e obrigatorio',
            'date' => ':attribute deve ser uma data válida',
            'exists' => ':attribute inválido',
            'array' => ':attribute deve ser um array',
            'min' => ':attribute deve ter no mínimo :min',
            'integer' => ':attribute deve ser uma número inteiro',
            'numeric' => ':attribute deve ser uma número válido',
        ];

    }
}
