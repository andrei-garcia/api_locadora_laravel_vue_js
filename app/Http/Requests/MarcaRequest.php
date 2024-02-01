<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarcaRequest extends FormRequest
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
            'nome' => 'required|unique:marcas,nome',
            'imagem' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
        
        if ($this->method() == 'PATCH' || $this->method() == 'PUT') {
            $rules['nome'] = 'required|unique:marcas,nome,' . $this->route('marca') . ',id';

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
            'imagem.max' => 'O campo imagem deve ter no máximo 2048 bytes'
        ];
    }
}
