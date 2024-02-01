<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarroRequest extends FormRequest
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
        $rules =  [
            'modelo_id' => 'exists:modelos,id',
            'placa' => 'string|max:7',
            'km' => 'numeric',
            'disponivel' => 'boolean'
        ];

        if ($this->method() == 'PATCH') {
            
            $inputs = $this->all();
            
            if(count($inputs) > 0){

                foreach ($rules as $key => $value) {
                
                    if (!isset($inputs[$key])) {
                        unset($rules[$key]);
                    }
                   
                }
            }
        }
        
        return $rules;
        
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'modelo_id.exists' => 'O modelo_id informado não existe',
            'placa.string' => 'O campo placa deve ser uma string',
            'placa.max' => 'O campo placa deve ter no máximo 7 caracteres',
            'km.numeric' => 'O campo km deve ser um número',
            'disponivel.boolean' => 'O campo disponivel deve ser um booleano'
        ];
    }
}
