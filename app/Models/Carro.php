<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    use HasFactory;

    protected $fillable = [
        'modelo_id',
        'placa',
        'km',
        'disponivel'
    ];

    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }
}
