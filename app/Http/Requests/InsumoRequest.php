<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsumoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'unidade_medida' => ['required', 'string', 'max:50'],
            'quantidade_minima' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome é obrigatório.',
            'unidade_medida.required' => 'A unidade de medida é obrigatória.',
            'quantidade_minima.required' => 'Informe a quantidade mínima.',
        ];
    }
}
