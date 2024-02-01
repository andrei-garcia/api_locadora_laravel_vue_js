<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class AbstrasticRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectAttributesRelacionais($atributes)
    {
        $this->model = $this->model->with($atributes);
    }

    public function selectFilter($filters)
    {
        $filtros = explode(';', $filters);
        foreach ($filtros as $filtro) {
            $f = explode(':', $filtro);

            if(count($f) != 3){
                continue;
            }

            $this->model = $this->model->where($f[0], $f[1], $f[2]);
        }
    }

    public function selectAttributes($atributes)
    {
        $this->model = $this->model->selectRaw($atributes);
    }

    public function getResult()
    {
        return $this->model->get();
    }
    
}