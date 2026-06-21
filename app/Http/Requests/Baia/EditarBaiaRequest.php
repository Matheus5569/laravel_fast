<?php

namespace App\Http\Requests\Baia;

use Illuminate\Foundation\Http\FormRequest;

class EditarBaiaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'id' => [
                'required',
                'integer',
                'exists:tb_baia,id'
            ],

            'nome' => [
                'required',
                'string',
                'max:100'
            ],

            'descricao' => [
                'nullable',
                'string'
            ],

            'status' => [
                'required',
                'in:Livre,Ocupada,Manutencao'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute é obrigatório',
            'string' => ':attribute deve ser texto',
            'max' => ':attribute ultrapassou o limite',
            'in' => ':attribute inválido',
        ];
    }
}