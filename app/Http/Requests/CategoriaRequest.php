<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoriaRequest extends FormRequest
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
        $rules = [
            'nome' => 'required',
            'descricao' => 'required'
        ];
        if ($this->_method !== 'PUT') {
            $rules['url_capa'] = 'required|file';
        }
        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        $messages = [
            'nome.required' => 'O campo nome é obrigatório',
            'descricao.required' => 'O campo descrição é obrigatório'
        ];

        if ($this->_method !== 'PUT') {
            $messages['url_capa.required'] = 'O campo foto da capa é obrigatório';
        }
        return $messages;
    }
}
