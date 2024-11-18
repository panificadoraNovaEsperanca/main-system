<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producao extends Model
{
    use HasFactory;
    protected $fillable = [
        'quantidade',
        'status',
        'dt_inicio',
        'produto_id',
        'baixa_id'
    ];

    public function produto(){
        return $this->hasOne(Produto::class,'id','produto_id');
    }

    public function user(){
        return $this->hasOne(User::class,'id','baixa_id');
    }
}
