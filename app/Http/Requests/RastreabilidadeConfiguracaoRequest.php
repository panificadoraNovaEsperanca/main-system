<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RastreabilidadeConfiguracaoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'produtos' => 'required|array',
            'produtos.*.produto_id' => 'required|exists:produtos,id',
            'produtos.*.ativo' => 'nullable|boolean',
            'produtos.*.insumos' => 'nullable|array',
            'produtos.*.insumos.*.insumo_id' => 'required_with:produtos.*.insumos|exists:insumos,id',
            'produtos.*.insumos.*.ordem' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'produtos.required' => 'É necessário informar pelo menos um produto.',
            'produtos.array' => 'Os produtos devem ser informados em formato de array.',
            'produtos.*.produto_id.required' => 'O ID do produto é obrigatório.',
            'produtos.*.produto_id.exists' => 'O produto selecionado não existe.',
            'produtos.*.insumos.*.insumo_id.required_with' => 'O insumo é obrigatório quando ingredientes são informados.',
            'produtos.*.insumos.*.insumo_id.exists' => 'O insumo selecionado não existe.',
            'produtos.*.insumos.*.ordem.integer' => 'A ordem deve ser um número inteiro.',
            'produtos.*.insumos.*.ordem.min' => 'A ordem deve ser maior ou igual a zero.',
        ];
    }
}
