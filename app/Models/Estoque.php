<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    use HasFactory;
    protected $fillable = [
        'insumo_id',
        'tipo',
        'valor',
        'descricao',
        'user_id'
    ];

    public function insumo(){
        return $this->hasOne(Insumo::class,'id','insumo_id');
    }

    public function operador(){
        return $this->hasOne(User::class,'id','user_id');
    }
}
