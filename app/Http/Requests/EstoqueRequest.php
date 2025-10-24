<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EstoqueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'insumo_id' => ['required', 'integer', 'exists:insumos,id'],
            'tipo'      => ['required', 'string', 'in:entrada,saida'], // ajuste os valores se for diferente
            'valor'     => ['required', 'numeric', 'min:0'],
            'descricao' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'insumo_id.required' => 'O insumo é obrigatório.',
            'insumo_id.exists'   => 'O insumo selecionado não existe.',
            'tipo.required'      => 'O tipo é obrigatório.',
            'tipo.in'            => 'O tipo deve ser entrada ou saída.',
            'valor.required'     => 'O valor é obrigatório.',
            'valor.numeric'      => 'O valor deve ser numérico.',
        ];
    }
}
