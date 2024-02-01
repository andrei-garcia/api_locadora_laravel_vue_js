<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModeloRequest extends FormRequest
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
            'nome' => 'required|unique:modelos,nome',
            'imagem' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'marca_id' => 'required|exists:marcas,id',
            'numero_portas' => 'required|integer',
            'lugares' => 'required|integer',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean'
        ];
        
        if ($this->method() == 'PATCH' || $this->method() == 'PUT') {
            $rules['nome'] = 'required|unique:modelos,nome,' . $this->route('modelo') . ',id';

            $inputs = $this->all();
            
            if(count($inputs) > 0){

                foreach ($rules as $key => $value) {
                
                    if ($this->method() == 'PATCH' && !isset($inputs[$key])) {
                        unset($rules[$key]);
                    }
                   
                }
            }
        }
        
        return $rules;
    }

    public function messages()
    {
        return [
            'unique' => 'o campo :attribute já está cadastrado',
            'nome.required' => 'O campo nome é obrigatório',
            'imagem.required' => 'O campo imagem é obrigatório',
            'imagem.image' => 'O campo imagem deve ser uma imagem',
            'imagem.mimes' => 'O campo imagem deve ser do tipo: jpeg, png, jpg, gif, svg',
            'imagem.max' => 'O campo imagem deve ter no máximo 2048 bytes',
            'marca_id.required' => 'O campo marca é obrigatório',
            'marca_id.exists' => 'A marca informada não existe',
            'numero_portas.required' => 'O campo numero_portas é obrigatório',
            'numero_portas.integer' => 'O campo numero_portas deve ser um número inteiro',
            'lugares.required' => 'O campo lugares é obrigatório',
            'lugares.integer' => 'O campo lugares deve ser um número inteiro',
            'air_bag.required' => 'O campo air_bag é obrigatório',
            'air_bag.boolean' => 'O campo air_bag deve ser um booleano',
            'abs.required' => 'O campo abs é obrigatório',
            'abs.boolean' => 'O campo abs deve ser um booleano'
        ];
    }
}
