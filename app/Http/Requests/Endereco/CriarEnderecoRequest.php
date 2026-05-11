<?php

namespace App\Http\Requests\Endereco;

use Illuminate\Foundation\Http\FormRequest;

class CriarEnderecoRequest extends FormRequest
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
            'cep'       => ['required', 'string', 'max:8'],
            'rua'       => ['required', 'string'],
            'numero'     => ['required', 'string'],
            'complemento' => ['nullable', 'string'],
            'bairro'     => ['required', 'string'],
            'cidade'    => ['required', 'string'],
            'estado'     => ['required', 'string', 'max:2'],
        ];

    }
    public function messages()
    {
        return [
            'required' => ':attribute e obrigatório',
            'string' => ':attribute deve ser uma string',
            'max' => ':attribute deve ser maximo: max caracteres',
        ];
    }
}
