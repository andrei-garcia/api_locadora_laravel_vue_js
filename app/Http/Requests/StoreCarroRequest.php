<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarroRequest extends FormRequest
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
            'modelo_id' => 'required|exists:modelos,id',
            'placa' => 'required|string|max:7',
            'km' => 'required|numeric',
            'disponivel' => 'required|boolean'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'modelo_id.required' => 'O campo modelo_id é obrigatório',
            'modelo_id.exists' => 'O modelo_id informado não existe',
            'placa.required' => 'O campo placa é obrigatório',
            'placa.string' => 'O campo placa deve ser uma string',
            'placa.max' => 'O campo placa deve ter no máximo 7 caracteres',
            'km.required' => 'O campo km é obrigatório',
            'km.numeric' => 'O campo km deve ser um número',
            'disponivel.required' => 'O campo disponivel é obrigatório',
            'disponivel.boolean' => 'O campo disponivel deve ser um booleano'
        ];
    }
}
