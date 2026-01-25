<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RastreabilidadeRequest extends FormRequest
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
            'produto_rastreavel_id' => 'required|exists:produtos_rastreaveis,id',
            'lote' => 'required|string|size:4',
            'data_producao' => 'required|date',
            'responsavel' => 'nullable|string|max:255',
            'nao_produzido' => 'nullable|boolean',
            'ingredientes' => 'nullable|array',
            'ingredientes.*.insumo_id' => 'required_with:ingredientes|exists:insumos,id',
            'ingredientes.*.marca' => 'nullable|string|max:255',
            'ingredientes.*.lote_ingrediente' => 'nullable|string|max:255',
            'ingredientes.*.validade_original' => 'nullable|date',
            'ingredientes.*.data_abertura' => 'nullable|date',
            'ingredientes.*.validade_apos_aberto' => 'nullable|date',
            'ingredientes.*.caracteristica_sensorial' => 'nullable|string|max:255',
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
            'produto_rastreavel_id.required' => 'O produto rastreável é obrigatório.',
            'produto_rastreavel_id.exists' => 'O produto rastreável selecionado não existe.',
            'lote.required' => 'O número do lote é obrigatório.',
            'lote.size' => 'O lote deve ter exatamente 4 caracteres (formato MMDD).',
            'data_producao.required' => 'A data de produção é obrigatória.',
            'data_producao.date' => 'A data de produção deve ser uma data válida.',
            'ingredientes.*.insumo_id.required_with' => 'O insumo é obrigatório quando ingredientes são informados.',
            'ingredientes.*.insumo_id.exists' => 'O insumo selecionado não existe.',
        ];
    }
}
