<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PedidoRequest extends FormRequest
{
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
            'motorista' => 'required',
            'dataHora' => 'required',
            'produto' => 'required|array|min:1',
            'produto.*' => 'required|exists:produtos,id',
            'quantidade' => 'required|array|min:1',
            'quantidade.*' => 'required|numeric|min:0.1',
            'cliente' => 'required',
            'cliente_id' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'motorista.required' => 'O campo motorista é obrigatório',
            'dataHora.required' => 'O campo data e hora é obrigatório',
            'produto.required' => 'É necessário adicionar pelo menos um produto',
            'produto.min' => 'É necessário adicionar pelo menos um produto',
            'produto.*.required' => 'Todos os produtos devem ser selecionados',
            'quantidade.required' => 'É necessário informar a quantidade dos produtos',
            'quantidade.min' => 'É necessário informar a quantidade dos produtos',
            'quantidade.*.required' => 'A quantidade é obrigatória',
            'quantidade.*.numeric' => 'A quantidade deve ser um número',
            'quantidade.*.min' => 'A quantidade deve ser maior que zero',
            'cliente.required' => 'O campo cliente é obrigatório',
            'cliente_id.required' => 'O campo cliente é obrigatório',
        ];
    }
}
