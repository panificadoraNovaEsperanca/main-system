<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdutoRequest extends FormRequest
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
            "nome" => "required",
            "unidade_medida" => "required",
            "categoria" => "gt:-1",
            "marca" => "gt:-1",
            "fornecedor" => "gt:-1",
            "porcao" => "required",
            "proteina" => "required",
            "carboidrato" => "required",
            "gordura_total" => "required",
            // "responsavel" => "required",
        ];
    }

    public function messages()
    {
        return [
            "nome.required" => "Obrigatório",
            "unidade_medida.required" => "Obrigatório",
            "categoria.gt" => "Obrigatório",
            "marca.gt" => "Obrigatório",
            "fornecedor.gt" => "Obrigatório",
            "porcao.required" => "Obrigatório",
            "proteina.required" => "Obrigatório",
            "carboidrato.required" => "Obrigatório",
            "gordura_total.required" => "Obrigatório",
            // "responsavel.required" => "O campo responsavel é obrigatório",
        ];
    }
}
