<?php

namespace App\Http\Requests\Caminhoneiro;

use Illuminate\Foundation\Http\FormRequest;

class CriarCaminhoneiroRequest extends FormRequest
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

            'nome' => ['required', 'string', 'max:100'],

            'cpf' => ['required', 'string', 'max:14'],

            'telefone' => ['required', 'string', 'max:20'],

            'placa_caminhao' => ['required', 'string', 'max:20'],

            'status' => [
                'required',
                'in:Livre,EmViagem,Folga'
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
